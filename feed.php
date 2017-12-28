<?php
	header("Content-Type: application/rss+xml; charset=ISO-8859-1");
	$dbc= mysql_connect('db_host','username','password') or die('ERROR:Problem Connecting server');
	mysql_select_db("db_name", $dbc);
 
	$pid=strip_tags($_GET['pid']);
	$query = "SELECT title FROM post WHERE post_id=".$pid." LIMIT 1";
    $result = mysql_query($query) or die ("Could not execute query");
	
	$row1=mysql_fetch_array($result,MYSQL_ASSOC);
	
	$rssfeed = '<?xml version="1.0" encoding="ISO-8859-1"?>';
    $rssfeed .= '<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">';
    $rssfeed .= '<channel>';
    $rssfeed .= '<title>'.$row1['title'].'---RSS feed</title>';
    $rssfeed .= '<ttl>5</ttl>';
    $rssfeed .= '<atom:link href="http://forum.abhibhatia.co.cc/feed.php?pid='.$pid.'" rel="self" type="application/rss+xml" />';
    $rssfeed .= '<link>http://forum.abhibhatia.co.cc/posts.php?pid='.$pid.'</link>';
	$rssfeed .= '<webMaster>webmaster@abhatia.co.cc</webMaster>';
    $rssfeed .= '<description>This is an RSS feed of a post on forum @ abhibhatia.co.cc</description>';
    $rssfeed .= '<language>en-us</language>';
    $rssfeed .= '<copyright>Copyright (C) 2012 forum.abhibhatia.co.cc</copyright>';
	
	$query = "SELECT * FROM threads WHERE pid=".$pid." ORDER BY date DESC";
    $result = mysql_query($query) or die ("Could not execute query");
	$n=mysql_num_rows($result);
	$a=new DateTime();
	for($i=0;$i<$n;$i++){
		$row=mysql_fetch_array($result,MYSQL_ASSOC);
		//$date = DateTime::createFromFormat('Y-m-d H:i:s', $row['date']);
		//echo $date;echo"ji";
		//echo $date->format('D, d M y H:i:s');
		$a=date('Y-m-d H:i:s',strtotime($row['date']));
		//$a->setTimeStamp($row['date']);
		//$date = date_create_from_format('M d, Y * h:i A', $row['date']);
		$rssfeed .= '<item>';
        $rssfeed .= '<title>Comment On "'.$row1['title'].'" </title>';
        $rssfeed .= '<description><![CDATA[' .$row['content']. '<br><p align=right>by '.$row['by'].']]></description>';
        $rssfeed .= '<link>http://forum.abhibhatia.co.cc/posts.php?pid='.$pid.'</link>';
        $rssfeed .= '<pubDate>' .$a. '</pubDate>';
       // $rssfeed .= '<author>' .$row['by']. '</author>';
		$rssfeed .= '</item>';
    }

    $rssfeed .= '</channel>';
    $rssfeed .= '</rss>';

    echo $rssfeed;
?>
	