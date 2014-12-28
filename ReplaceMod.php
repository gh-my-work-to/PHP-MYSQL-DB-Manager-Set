<?php
class ReplaceMod
{
	function getReplaced($l_data)
	{
		foreach ($l_data as $key => $v)
		{
			$l_data[$key] = preg_replace("/<>/", "", $v);
		}
		
		extract($l_data);
		
		if($header == null)
		{
			$header = $title;
		}
				
		$l_fp = fopen($path, 'r') or die("fopen error!");
		$l_buf = "";
		while(($l_line = fgets($l_fp)) != null)
		{
			$l_line = preg_replace("/{{title}}/", $title, $l_line);
			
			$l_line = preg_replace("/{{header}}/", $header, $l_line);
					
			$l_line = preg_replace("/{{header_bgc}}/", $header_bgc, $l_line);
			
			$l_line = preg_replace("/{{desc}}/", $desc, $l_line);
			
			$l_line = preg_replace("/{{img}}/", $img, $l_line);
			
			$l_line = preg_replace("/{{link_text}}/", $link_text, $l_line);
			
			$l_buf .= $l_line;
		}
		fclose($l_fp);
		return $l_buf;
	}

}
?>
