<?php

/*
 * Snippet function
 *
 *     Checks in the text after words that has the first character given one set by user, and if we have them in database, we replace them
 *     with the long description. We use cache to reduce the SQL request to database.
 *
 *      require_once("snippet.class.php");
 *      $snippet = new Snippet('localhost', 'root', '', 'snippet_db', '#');
 *      echo $snippet->get_snippet('ro');
 *      echo $snippet->replace_string("Good morning #la!!!", "la");
 *
 *      //Result(string 'la' it's in db):
 *
 *      // Good morning Los Angeles!!!
 *
 *
 *      echo $snippet->replace_string("Good morning #ca!!!", "ca");
 *
 *      //Result(we don't have string 'ca' in db):
 *
 *      // Good morning ca!!!
 *
 */

require_once("database.class.php");

class Snippet
{
    public    $first_char = '#';
    protected $cached_data;

    public function __construct($server, $username, $password, $db, $first_char)
    {
        //database variables
        $this->server    = $server;
        $this->username  = $username;
        $this->password  = $password;
        $this->db        = $db;

        // plugin variables
        $this->first_char = $first_char;
    }

    // return the description of a snippet that is already saved in cache
    public function get_snippet_cache($term)
    {
        return $this->cached_data[$term];
    }

    //save in cache a snippet
    public function set_snippet_cache($label, $value)
    {
        $this->cached_data[$label] = $value;
    }

    //return all snippets from cache
    public function cache_snippets()
    {
        return $this->cached_data;
    }

    //replace a snippet in a text
    public function replace_string($text, $term)
    {

        $data = $this->cache_snippet($term);

        if(isset($data[$term])) {
            $replace = $data[$term];
        } else {
            $replace = $term;
        }
        return preg_replace('/'.$this->first_char.$term.'/i', $replace, $text);
    }

    //replace all snippets from text
    public function parse_string ($text)
    {
        $pattern = "/\s*".$this->first_char."([a-zA-Z0-9-_]+?)(\s|\p{P}|$)/i";

        if (preg_match_all($pattern, $text, $matches)) {

            foreach($matches['1'] as $match) {
                $text = $this->replace_string($text, $match);
            }

        }
        return $text;
    }

    //return all the snippet name that starts with the $term, without the first character (the default one, e.g. #)
    public function snippet_suggestion($term)
    {
        $rest = substr($term, 1);
        $data = $this->db_snippet($rest, true);
        $result = array();
        foreach($data as $value) {
            $result[] = array (
                'label' => $value['label'],
                'value' => $value['label'],
                );
        }
        return json_encode($result);
    }

    //check in database if snippet exist
    protected function db_snippet($term, $more = false)
    {
        $dbc = new DBConnection($this->server, $this->username, $this->password, $this->db);
        $dbc->__wakeup();

        // query the database table for zip codes that match 'term'
        $query = 'SELECT  name, description FROM  snippet';

        if($more == true) {
            $where = ' WHERE name LIKE "'. mysql_real_escape_string($term) .'%"';
        } else {
            $where = ' WHERE name = "'. mysql_real_escape_string($term) .'"';
        }

        $query .= $where;
        $rs = mysql_query($query, $dbc->link);

        $data = array();

        while($row = mysql_fetch_array($rs, MYSQL_ASSOC)) {
            $data[] = array(
                'label' => $row['name'],
                'value' => $row['description']
            );
        }
        return $data;
    }

    //check if snippet it's allready in cache, and add it if it's not
    protected function cache_snippet($term)
    {
        $data = false;

        if (isset($this->cached_data) && $this->array_key_exists_r($term, $this->cached_data)) {

            $data = array(
                $term => $this->cached_data[$term]
                );
        } else {

            $data = $this->db_snippet($term);
            if(isset($data['0'])) {
                $this->cached_data[$term] = $data['0']['value'];
                $this->set_snippet_cache($term, $data['0']['value']);

                 $data = array(
                            $term => $this->cached_data[$term]
                    );

                }
            }
        return $data;
    }

    //check if a key exist in a multidimensional array
    private function array_key_exists_r($needle, $haystack)
    {
        $result = array_key_exists($needle, $haystack);

        if ($result) return $result;

        foreach ($haystack as $v) {

            if (is_array($v)) {
                $result = self::array_key_exists_r($needle, $v);
            }

            if ($result) return $result;
        }
        return $result;
    }
}

?>