<?php
		 
  $graph = array();
	foreach ($_POST as $oneGraph => $value) {
		if ($value <> 0 && $oneGraph <> 'source' && $oneGraph <> 'target' && $oneGraph <> 'send_graph'){
			$firstPoint = substr($oneGraph,5,1);
			if (empty($graph[$firstPoint])){
				$graph[$firstPoint] = array();
				$secondPoint = substr($oneGraph,6,1);
				$graph[$firstPoint][$secondPoint] = $value;
		
				if (empty($graph[$secondPoint][$firstPoint])){
					$graph[$secondPoint][$firstPoint] = $graph[$firstPoint][$secondPoint];
				}
			}else{
				$secondPoint = substr($oneGraph,6,1);
				$graph[$firstPoint][$secondPoint] = $value;
				if (empty($graph[$secondPoint][$firstPoint])){
					$graph[$secondPoint][$firstPoint] = $graph[$firstPoint][$secondPoint];
				}
			}
		}
	}

	class Algorithm
	{
		protected $graph;
		protected $res;
		 
		public function __construct($graph, $res) {
			$this->graph = $graph;
			$this->res = $res;
		}
		//визначення найкоротшого шляху між двома заданими точками 
		public function shortestPath($source, $target) 
		{
			$shortPath = array();//масив найкоротших шляхів
			$prevNode = array();//масив попередників
				
			$queueNodes = new SplPriorityQueue();//черга всіх вузлів
			 
			foreach ($this->graph as $node => $bondage) {
				$shortPath[$node] = INF; //встановлення початкових шляхів в безкінечність
				$prevNode[$node] = null; //вузлів позаду немає
				
				foreach ($bondage as $bondNode => $cost) {
					$queueNodes->insert($bondNode, $cost);
				}
			}
			// початкова дистанція на стартовому вузлі - 0
			$shortPath[$source] = 0;
		 
			while (!$queueNodes->isEmpty()) {
				//вибір мінімальної вартості
				$node = $queueNodes->extract();
			  
				if (!empty($this->graph[$node])) {
					//прохід по всім сусіднім вузлам
					foreach ($this->graph[$node] as $nameNode => $cost) 
					{
						//встановлення нової довжини шляху для сусіднього вузла
						$alt = $shortPath[$node] + $cost;
						//якщо вузол коротший
						if ($alt < $shortPath[$nameNode]) {
							$shortPath[$nameNode] = $alt;//встановлюється як мінімальна відстань до вузла
							$prevNode[$nameNode] = $node;//додавання сусіда як попередника вузла
						}
					}
				}	
			}
			//використовуючи зворотній прохід, визначається мінімальний шлях
			$shortStack = new SplStack();//найкоротший шлях як стек
			$node = $target;
			$dist = 0;
			//прохід від цільового вузла до стартового
			while (isset($prevNode[$node]) && $prevNode[$node]) 
			{
				$shortStack->push($node);
				$dist += $this->graph[$node][$prevNode[$node]];//додавання дистанції для попередників
				$node = $prevNode[$node];
			}
			//стек буде пустий, якщо нема шляху назад
			if ($shortStack->isEmpty()) {
				$this->res .= "Немає шляху із ".$source." в ".$target;
			}
			else {
				//добавлення стартового вузла, виведення всього шляху
				$shortStack->push($source);
				$this->res .= "$dist:  ";
				$sep = '';
					foreach ($shortStack as $v) {
						$this->res .= $sep.$v;
						$sep = '->';
					}
			}
			return $this->res;
		}
	}
	$g = new Algorithm($graph, '');
	
	$res["res"] = $g->shortestPath($_POST['source'], $_POST['target']); 
	
	echo json_encode($res);		
?>