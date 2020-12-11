<?php

class ShortUrl {

	public function short_url($url) { 
		$url=crc32($url); 
  		$result=sprintf("%u",$url); 
  		return $this->code62($result); 
	}

	function code62($x){ 
	    $show=''; 
	    while($x>0){ 
	        $s=$x % 62; 
	        if ($s>35){ 
	          $s=chr($s+61); 
	        } elseif ($s>9&&$s<=35){ 
	          $s=chr($s+55); 
	        } 
	        $show.=$s; 
	        $x=floor($x/62); 
	    } 
	    return $show; 
	}  
}

?>