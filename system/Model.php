<?php
/**
 * System Model
 *
 * @author Simeon Lyubenov (ShakE) <office@webdevlabs.com>
 * @link https://www.webdevlabs.com
 */
namespace System;

class Model
{

    /**
     * add item to database
     *
     * @param [array] $data
     * @param [array] $langdata
     * @return int
     */
    public function add(array $data, array $langdata = null)
    {
        $insert_id = DB::insert($this->table, $data);
        if ($langdata) {
            $langdata[$this->table_id] = $insert_id;
            DB::insert($this->table_text, $langdata);
        }
        return $insert_id;
    }

    /**
     * update item in database
     *
     * @param [int] $id
     * @param [array] $data
     * @param [array] $langdata
     * @return void
     */
    public function update(string $id, array $data, array $langdata = null)
    {
        $newlangdata = $langdata;
        if ($data) {
            DB::update($this->table, $data, $id, $this->table_id); // update main data
        }
        if ($langdata) {
            $langdata[$this->table_id] = $id;
            $query = "INSERT INTO `{$this->table_text}` (`" . implode('`, `', array_keys($langdata)) . "`)
            VALUES (" . rtrim(str_repeat('?, ', count($langdata = array_values($langdata))), ', ') . ")";
            $newlangdata = $this->array_keys_prefix($newlangdata);
            $query .= " ON DUPLICATE KEY UPDATE " . implode(', ', array_keys($newlangdata)) . " ";
            DB::query($query, $langdata); // insert/update data for selected language
        }
    }

    /**
     * Prefix array values for update function
     *
     * @param array $arr
     * @return array
     */
    private function array_keys_prefix(array $arr)
    {
        $rarr = [];
        foreach ($arr as $key => $val) {
            $rarr["$key=VALUES($key)"] = $val;
        }
        return $rarr;
    }

    /**
     * delete item from database
     *
     * @param string $id
     * @param string $lang
     * @return void
     */
    public function delete(string $id, string $lang = null)
    {
        if (!$lang) {
            // if no language choosen -> delete from everywhere
            return DB::query("DELETE from `{$this->table}` WHERE `{$this->table_id}`=?", [$id]);
        } else {
            // if language is set -> delete only the multi lang data for the lang
            return DB::query("DELETE from `{$this->table_text}` WHERE `{$this->table_id}`=:id AND `lang`=:lang",
                ["id" => $id, "lang" => $lang]
            );
        }
    }

    /**
     * fetch single item from database
     *
     * @param string $id
     * @param string $colname
     * @param string $select_lang
     * @return void
     * @usage $this->model->find($page_id); $this->pages->find($uri,'uri');
     */
    public function find(string $id, string $colname = null, string $select_lang = null)
    {
        if (!$select_lang) {$select_lang = lang::$language;}
        if (!$colname) {$colname = $this->table_id;}
        $query = "SELECT * from `{$this->table}` ";
        if ($this->table_text) {
            $query .= "LEFT JOIN `{$this->table_text}` USING ({$this->table_id}) ";
        }
        
        $query .= "WHERE `$colname`=:id ";
        if ($this->table_text) {
            $query .= " AND (`lang`='" . lang::$default_lang . "' OR `lang`='" . $select_lang . "')
                        ORDER BY FIELD(lang,'" . $select_lang . "','" . lang::$default_lang . "') ";
        }
        $query .= "LIMIT 1";
        $data = DB::row($query, [":id" => $id]);
        return $data;
    }

    /**
     * fetch all items
     *
     * @param [string] $sql
     * @param [array] $params
     * @param [int] $offset
     * @return array
     * @usage $this->model->findAll(); $this->model->findAll("(`title` LIKE :title)", ["title"=>"%{$title}%"]);
     */
    public function findAll(string $sql = null, array $params = null, string $offset = null)
    {
        // assign language
        if (!$params['lang'] && $this->table_text) {
            $params['lang'] = lang::$language;
        } 
        if (!$this->table_text) {
            unset($params['lang']);
        }
        if ($sql) {
            if ($this->table_text) {
                $where .= "AND " . $sql;
            } else {
                $where .= $sql;
            }
        }

        if (isset($offset)) {
            $pagination = "LIMIT $offset, " . $this->conf->items_per_page;
        }
        $query = "SELECT * from `{$this->table}` ";
        if ($this->table_text) {
            $query .= "LEFT JOIN `{$this->table_text}` USING(`{$this->table_id}`) ";
        }
        $query .= " WHERE ";
        if ($this->table_text) {
            $query .= "(`lang`=:lang
            OR (`lang`='" . lang::$default_lang . "'
                AND NOT EXISTS(
                        SELECT `lang` FROM `{$this->table_text}`
                        WHERE `lang`=:lang AND `{$this->table_id}`=`{$this->table}`.`{$this->table_id}`
                    )
                )
            ) ";
        }
        $query .= "$where GROUP BY `{$this->table}`.`{$this->table_id}` $pagination";
        $data = DB::fetch($query, $params);

        return $data;
    }

/* custom nested for testing */
/**
 * search items in database
 *
 * @param string $sql
 * @param array $params
 * @return array
 * @usage
 * $this->model->search("`active`='1' AND (`title` LIKE :title)",['title'=>"%{$title}%"])->limit(10)->run();
 * $this->model->search()->limit(10)->run();
 * $this->model->search()->count()->run();
 */
    public function search(string $sql = null, array $params = null)
    {
        // assign language
        if (!$params['lang'] && $this->table_text) {
            $params['lang'] = lang::$language;
        } else {
            unset($params['lang']);
        }
        if ($sql) {
            if ($this->table_text) {
                $where .= "AND " . $sql;
            } else {
                $where .= $sql;
            }
        }
        $this->params = $params;
        $this->query = "`{$this->table}` ";
        if ($this->table_text) {
            $this->query .= "LEFT JOIN `{$this->table_text}` USING(`{$this->table_id}`)	";
        }
        $this->query .= "%query_join%";
        $this->query .= "WHERE ";
        if ($this->table_text) {
            $this->query .= "(`lang`=:lang
                                    OR (`lang`='" . lang::$default_lang . "'
                                    AND NOT EXISTS(
                                        SELECT `lang` FROM `{$this->table_text}`
                                        WHERE `lang`=:lang AND `{$this->table_id}`=`{$this->table}`.`{$this->table_id}`
                                        )
                                    )
                                ) ";
        }
        $this->query .= " $where";
        // $where GROUP BY `{$this->table}`.`{$this->table_id}`
        return $this;
    }

    /**
     * experimental, not tested
     *
     * @param string $mod_uri = 'page'
     * @param string $module_name = 'pages'
     * @return void
     */
    public function withSeo(string $mod_uri, string $module_name)
    {
        $this->query_join_seo .= "SELECT `{$this->table}`.*,";
        if ($this->table_text) {
            $this->query_join_seo .= "`{$this->table_text}`.*,";
        }
        $this->query_join_seo .= "MIN(IF(ISNULL(`seo_urls`.`from`),CONCAT('{$mod_uri}/',`{$this->table}`.`uri`),`seo_urls`.`from`)) as `seourl` from `{$this->table}`
		LEFT JOIN `seo_urls` ON (`seo_urls`.`to`=CONCAT('{$mod_uri}/',`{$this->table}`.`uri`) AND `seo_urls`.`module`='{$module_name}') ";
        return $this;
    }

    public function join(string $model, string $keyname)
    {
        $model = $this->load->model($model);
        $this->query_join .= " JOIN `{$table}` ON (`{$this->table}`.`{$this->table_id}`=`{$table}`.`{$table_id}`) ";
        return $this;
    }

    public function limit(int $offset)
    {
        $this->query_limit = " LIMIT $offset, " . $this->conf->items_per_page;
        return $this;
    }

    public function count()
    {
        $this->query_column = "SELECT COUNT(DISTINCT `{$this->table_id}`) from ";
        return $this;
    }

    public function max(string $max)
    {
        $this->query_column = "SELECT MAX({$max}) from ";
        return $this;
    }

    public function min(string $min)
    {
        $this->query_column = "SELECT MIN({$min}) from ";
        return $this;
    }

    public function sum(string $sum)
    {
        $this->query_column = "SELECT SUM({$sum}) from ";
        return $this;
    }

    public function order(string $field, bool $asc = true)
    {
        $this->query_order = " ORDER BY `{$field}` " . ($asc ? 'ASC' : 'DESC');        
        return $this;
    }

    public function group(string $field)
    {
        $this->query_group = " GROUP BY {$field} ";
        return $this;
    }

    public function run()
    {
        if ($this->query_group) {
            $this->query .= $this->query_group;
        }
        if ($this->query_order) {
            $this->query .= $this->query_order;
        }
        if ($this->query_limit && !$this->query_column) {
            $this->query .= $this->query_limit;
        }        
        $this->query = str_replace('%query_join%', $this->query_join, $this->query);        
        if ($this->query_column) {
            $this->query = $this->query_column . $this->query;
            $data = DB::column($this->query, $this->params);
        } else {
            $this->query = "SELECT * from " . $this->query;
            $data = DB::fetch($this->query, $this->params);
        }
        $this->query_order = '';
        $this->query_group = '';
        $this->query_limit = '';
        $this->query_column = '';
        $this->params = '';
        $this->query = '';
//        print_r($this->query);
        //print_r($this->params);
        return $data;
    }

}
