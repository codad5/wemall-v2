<?php
// example of how to use basic selector to retrieve HTML contents
include('simple_html_dom.php');
 
// get DOM from URL or file
$html = file_get_html('https://www.google.com/search?q=ngn+to+usd');
// $html = file_get_html('https://www.google.com/search?q=how+to+create+google+login+with+laravel');

// find all link
foreach($html->find('a') as $e) 
    echo $e->href . '<br>';
    

// find all image
foreach($html->find('img') as $e)
    echo $e->src . 'hmm <br>';
    echo "<img src='$e->src' />";

// find all image with full tag
foreach($html->find('img') as $e)
    echo $e->outertext . '<br>';

// find all div tags with id=gbar
foreach($html->find('div#gbar') as $e)
    echo $e->innertext . '<br>';

// find all span tags with class=gb1
foreach($html->find('span.DFlfde ') as $e)
    echo   '<br>';
    echo   '<br>';
    echo   '<br>';
    echo $e->outertext . '<br>';
    echo   '<br>';
    echo   '<br>';
    echo   '<br>';

// find all td tags with attribite align=center
foreach($html->find('td[align=center]') as $e)
    echo $e->innertext . '<br>';
    
// extract text from table
echo $html->find('td[align="center"]', 1)->plaintext.'<br><hr>';

// extract text from HTML
echo $html->plaintext;
?>