<?php

class RestTest extends \PHPUnit_Framework_TestCase
{

	public $response;

	public function testgetResponse($array = array()){
		$expected = array(
			"0"=>"Take train 78A from Madrid to Barcelona. Sit in seat 45B.",
			"1"=>"Take the airport bus from Barcelona to Gerona Airport. No seat assignment.",
			"2"=>"From Gerona Airport, take flight SK455 to Stockholm. Gate 45B, seat 3A. Baggage drop at ticket counter 344.",
			"3"=>"From Stockholm, take flight SK22 to New York JFK. Gate 22, seat 7B.Baggage will we automatically transferred from your last leg.",
			"4"=>"You have arrived at your final destination."
		);
        $array = array("sorter"=>array(
        	"1"=>"Take the airport bus from Barcelona to Gerona Airport. No seat assignment.",
			"2"=>"You have arrived at your final destination.",
			"3"=>"From Stockholm, take flight SK22 to New York JFK. Gate 22, seat 7B.Baggage will we automatically transferred from your last leg.",
			"4"=>"Take train 78A from Madrid to Barcelona. Sit in seat 45B.",
			"5"=>"From Gerona Airport, take flight SK455 to Stockholm. Gate 45B, seat 3A. Baggage drop at ticket counter 344."
		));
		$this->response = $array['sorter'];
		$actual = $this->sortData();
		
		$this->assertEquals($expected, $actual);
		//var_dump($array);
	}

	private function sortData(){
		$data = $this->_sortData($this->getFromTo());
		
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
		return $result;
	}

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

	private function getFromTo(){
		$response = array();
		foreach($this->response as $key => $value){
			$response[$key]['leg'] = $value;
			$response[$key]['from'] = $this->getFrom($value);
			$response[$key]['to'] = $this->getTo($value);
		}
		return $response;
	}

	private function getFrom($string){
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

	private function startsWithUpper($str) {
	   $chr = mb_substr ($str, 0, 1, "UTF-8");
	   return mb_strtolower($chr, "UTF-8") != $chr;
	}

	private function stripWord($str) {
		$str = str_replace(".","",$str);
		$str = str_replace(",","",$str);
		return $str;
	}

}

?>