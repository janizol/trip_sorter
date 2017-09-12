<?php

class Rest {
	
	public $response;

	public function getResponse($array = array()){
		$this->response = $array['sorter'];
		$this->sortData();
		//var_dump($array);
	}

	/**
     * Sort data in order
     *
     * @return string[]
     */
	private function sortData(){
		$data = $this->_sortData($this->getFromTo());
		//var_dump($data);
		
		$counter = count($data);
		$result = array();
		foreach($data as $key => $value){
			if(isset($data[$key]['link']) && $data[$key]['link'] == $key){
				$result[$counter] = $data[$key]['leg'];
				$counter--;
				foreach($data as $key1 => $value1){
					if(empty($data[$key1]['link'])){
						$result[$counter] = $data[$key1]['leg'];
						$lastFrom = $data[$key1]['from'];
						unset($data[$key1]);
						$counter--;
					}
				}
			}
		}
   		$error['error'] = 0;
		do{
			$keepGoing = $counter;
			foreach($data as $key => $value){
				if($data[$key]['to'] == $lastFrom){
					$result[$counter] = $data[$key]['leg'];
					$lastFrom = $data[$key]['from'];
					unset($data[$key1]);
					$counter--;
				}
			}
			if($keepGoing == $counter){
				$keepGoing = 0;
				$error['error'] = 1;
				$error['message'] = "The data supplied is invalid.";
			}
		}while($counter > 0 && $keepGoing > 0);
		
		if($error['error'] == 1){
			$result = $error;
		} else {
			$result = array_reverse($result);
		}
		echo json_encode($result);
	}

	/**
     * Sort data in order
     *
     * @return array
     */
	private function _sortData($array = array()){
		$array2 = $array;
		foreach($array as $key => $value){
			foreach($array2 as $key2 => $value2){
				if($array[$key]['to'] == $array2[$key2]['from']){
					$array[$key]['link'] = $key2;
				}
			}
		}
		return $array;
	}

	/**
     * Get to and from text to array
     *
     * @return array
     */
	private function getFromTo(){
		$response = array();
		foreach($this->response as $key => $value){
			$response[$key]['leg'] = $value;
			$response[$key]['from'] = $this->getFrom($value);
			$response[$key]['to'] = $this->getTo($value);
		}
		return $response;
	}

	/**
     * Get the word after from
     *
     * @return string | null
     */
	private function getFrom($string){
		//Get the word after from
		preg_match('/(?<=from )\S+/i', $string, $match);
		if(!empty($match)){
			//go through results if more then 1
			foreach($match as $word){
				//if this word starts with caps (hSould be a places name)
				if($this->startsWithUpper($word)){
					return $this->stripWord($word);
				}
			}
		}
		return null;
	}

	/**
     * Get the word after to
     *
     * @return string | null
     */
	private function getTo($string){
		preg_match('/(?<=to )\S+/i', $string, $match);
		if(!empty($match)){
			//go through results if more then 1
			foreach($match as $word){
				//if this word starts with caps (hSould be a places name)
				if($this->startsWithUpper($word)){
					return $this->stripWord($word);
				}
			}
		}
		return null;
	}

	/**
     * Get whether the word starts with Caps
     *
     * @return bool
     */
	private function startsWithUpper($str) {
	    $chr = mb_substr ($str, 0, 1, "UTF-8");
	    return mb_strtolower($chr, "UTF-8") != $chr;
	}

	/**
     * Check whether ends in , or . and remove
     *
     * @return string
     */
	private function stripWord($str) {
		//I'm not sure how to achieve this in regex
		$str = str_replace(".","",$str);
		$str = str_replace(",","",$str);
	    return $str;
	}

}

?>