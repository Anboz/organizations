<?php

namespace App\Http\Controllers\Parsing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ParsController extends Controller
{		

		
		
		
	     function pars(Request $request)	   
		   {
			    
			   $lines = explode("\n", $this->ConvertPdfToText($request->input("url")));
			   
			  $organizations = $organization = $invalidArray = array();
			  $n = count($lines);
			  for ($i = 0; $i < $n; $i++) {
				  if (trim($lines[$i])!="") {
					  $elements = $this->filtrArray(explode(" ",trim($lines[$i])));
					    echo("<pre>");
					  print_r($elements);
					  echo("</pre>");   
					  if (!$this->invalidData($elements)) {

						  $organization['name'] = $this->getName($elements);
						  $organization['inn'] = $this->getInn($elements);
						  $organizations[] = $organization;
					  } else {
						  $invalidArray[] = $lines[$i];
					  }
				  }	 
			  	
			  }
			   $data = array($organizations, $invalidArray);
			   echo("<pre>");
			   print_r($data);
			   echo("</pre>"); 
		 
			    
			 // return(view("checkTextInfo", $data));
		   }
	
		   function ConvertPdfToText($url)
		   {

			   $data = json_decode(file_get_contents('http://api.rest7.com/v1/pdf_to_text.php?url=' . $url . '&layout=1'));
			   if (@$data->success !== 1) {
				   die('Failed');
			   }
			   $txt = file_get_contents($data->file);
			   // file_put_contents('data.txt', $txt);
			   return $txt;
		   }
	
	function invalidData($arr)
	{		 
		if (count($arr) < 3)
			return(true);
		if (!$this->allNumber($arr[0]))
			return(true);
		if ($this->isInn($arr[0]))
			return(true);
		if ($this->getInn($arr) == "")
			return(true);
		if ($this->getName($arr) =="")
			return(true);
		return false;
	}
	
	
	
	function isInn($str)
	{		 
		return(strlen($str) == 9 && $this->allNumber($str));
	}

 
	function isNumber($cr)
	{
		return preg_match("/[0-9]+/", $cr);
	}

	function allNumber($str)
	{		 
		for ($i = 0; $i < strlen($str); $i++)
			if(!$this->isNumber($str[$i]))
		return FALSE;
		return(true);
	}


	function getName($array)
	{
		$str = "";		
		for ($i = 1; $i < count($array); $i++)
		{
			if ($this->isInn($array[$i]))
				break;
			$str .= $array[$i]." ";
		}
		return(trim($str));
	}


	function getInn($array)
	{
		for ($i = 0; $i < count($array); $i++) {
			if ($this->isInn($array[$i]))
				return  $array[$i];
		}
		return "";
	}
	   
	function filtrArray($elements)
	{
		 $n = count($elements);
		 $arr = array();
		 for ($i = 0; $i < $n; $i++) {
			 if (trim($elements[$i])!="")
				 $arr[] = $elements[$i];
		 }
		 return($arr);
	 }  
	   
	   
	   
	  
   
   
}
