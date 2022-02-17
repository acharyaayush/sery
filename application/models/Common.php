<?php
class Common extends CI_Model
{
	public function __construct()
	{
		parent::__construct();	
		$this->load->database();
	}
	
	
	/************* EXICUTING CUSTOM QUERY ***************/
	// PARAMERETS :- 
	// 1). $query (string , required) :- any custom query 
	// 2). $type (string , optional) :- not required for insert , update and delete , "get" for select query
	
	function custom_query($query,$type = ""){
		$query = $this->db->query($query);
		if($type == "get"){
			//echo  $this->db->last_query();
			return $query->result_array();		
		}
	}
	
	/************* EXICUTING INSERT QUERY ***************/
	// PARAMERETS :- 
	// 1). $table_name (String , required) name of the table where you want to inset.
	// 2). $table_data (array , required) array of data to be insert ( array key = coloumn name , array value is value to be insert on that coloumn).
	
	function insertData($table_name,$table_data)
	{
		$this->db->insert($table_name,$table_data);
		//echo $this->db->last_query();
		return $this->db->insert_id(); 
	}
	
	/************* EXICUTING UPDATE QUERY ***************/
	// PARAMERETS :- 
	// 1). $tab_name (String , required) name of the table where you want to update.
	// 2). $tab_upd (array , required) array of data to be insert ( array key = coloumn name , array value is value to be update on that coloumn).
	// 3). $tab_where (string , optional) where condition string which row you want to update, can be blank if you want to upadte all rows.
	
	function updateData($tab_name,$tab_upd,$tab_where)
	{
		$this->db->where($tab_where);	
		$this->db->update($tab_name, $tab_upd);	
		return $this->db->affected_rows();
		
	}
	
	/************* EXICUTING DELETE QUERY ***************/
	// PARAMERETS :- 
	// 1). $tab_name (String , required) name of the table where you want to delete.
	// 2). $tab_where (string , optional) where condition string which row you want to delete, can be blank if you want to delete all rows.
	
	function deleteData($tab_name,$tab_where)
	{
		$this->db->delete($tab_name, $tab_where);	
		return $this->db->affected_rows();
		
	}
	
	
	/************* EXICUTING SELECT QUERY ***************/
	// PARAMERETS :- 
	// 1). $tab_name (String , required) name of the table where you want to update.
	// 2). $tab_sel (String , required) name of the coloumn want to select (*) for all coloumn, coloumn name by table refrence in case of join (tab1.col1 , tab1.col2 , tab2.col1 , tab2.col2 , tab3.col1).
	// 3). $tab_where (string , optional) where condition string which row you want to fetch, can be blank if you want to fetch all rows.
	// 4). $join (array , optional) name of the other tables in array formate to whome you want to join eg:- array("tab2","tab3"), can be blank if you dont want to join.
	// 5). $join_con (array , optional / required) array of join condition eg:- array("tab1.col1 = tab2.col2","tab1.col2 = tab3.col1"), required if you give value in $join array and can be blank if you dont want to join.
	// 6). $order_colname (string , optional) coloumn name by which you want to order, blank if you dont want to order.
	// 7). $order (string , optional / required) order of above selected coloumn "asc" or "desc" required if you using order and can be blank if you dont want to order.
	// 8). $no_of_record (integer , optional) number of record you want to fetch, blank if you dont want to limit your query.
	// 9). $start_from (integer , optional) offeset for limit from which index you want to select the records, can be blank if you want record from starting.
	
	function getData($tab_name,$tab_sel,$tab_where="",$join="",$join_con="",$order_colname="",$order="",$no_of_record="",$start_from="")
	{
		$this->db->select($tab_sel);
		if(!empty($join) && !empty($join_con)){
			for($i = 0;$i<count($join);$i++){
				$this->db->join($join[$i], $join_con[$i],'inner');	
			}
		}
		if(!empty($tab_where))
		{
			$this->db->where($tab_where);
		}
		if(!empty($order_colname) && !empty($order))
		{
			$this->db->order_by($order_colname, $order);
		}		
		if(!empty($no_of_record) && !empty($start_from)){
			
			$this->db->limit($no_of_record,$start_from);
		}		
		if(!empty($no_of_record) && empty($start_from)){
			
			$this->db->limit($no_of_record);
		}		
		$query=$this->db->get($tab_name);
		return $query->result_array();	
	}

	
	/************* EXICUTING CUONT RECORD QUERY ***************/
	// PARAMERETS :- 
	// 1). $tab_name (String , required) name of the table where you want to get number of record.
	// 2). $tab_where (string , optional) where condition on which you want to get number of record, can be blank if you want to get count of all record.
	
	function get_cnt_entries($tab_name,$tab_where = '')
	{	
		if(!empty($tab_where))
		{
			$this->db->where($tab_where);
		}
		$this->db->from($tab_name);
		$query = $this->db->count_all_results();
		return $query;
	}
	//-- Model Function for Pagination
	public function pagination($page, $total_pages)
	{
		$adjacents = 3;
		$page += 1;
		$limit = 10;
		$prev = $page - 1;	//previous page is page - 1
		$next = $page + 1;	//next page is page + 1
		$lastpage = $total_pages;
		$lpm1 = $lastpage - 1;	

		$pagination = "";
		if($lastpage > 0)
		{	
			$pagination .= "<ul class=\"pagination\">";
			//previous button
			if ($page > 1) 
				$pagination.= "<li class='page-item'><a class='page-link' href='#' onclick='getNextData($prev);'"."page=$prev><i class='fa fa-angle-left'></i></a></li>";
			else
				$pagination.= "<li class=\"disabled\"></li>";	
			
			//pages	
			if ($lastpage < 7 + ($adjacents * 2))	//not enough pages to bother breaking it up
			{	
				for ($counter = 1; $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<li class='page-item active'><a class=\"page-link\">$counter</a></li>";
					else
						$pagination.= "<li class='page-item'><a class='page-link' href='#' onclick='getNextData($counter);'"."page=$counter>$counter</a></li>";
				}
			}
			elseif($lastpage > 5 + ($adjacents * 2))	//enough pages to hide some
			{
				//close to beginning; only hide later pages
				if($page < 1 + ($adjacents * 2))		
				{
					for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
					{
						if ($counter == $page)
							$pagination.= "<span class=\"current\">$counter</span>";
						else
							$pagination.= "<li class='page-item'><a class='page-link' href='#' onclick='getNextData($counter);'"."page=$counter>$counter</a></li>";					
					}
					$pagination.= "...";
					$pagination.= "<li class='page-item'><a class='page-link' href='#' onclick='getNextData($lpm1);'"."page=$lpm1>$lpm1</a></li>";
					$pagination.= "<li class='page-item'><a class='page-link' href='#' onclick='getNextData($lastpage);'"."page=$lastpage>$lastpage</a></li>";		
				}
				//in middle; hide some front and some back
				elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
				{
					$pagination.= "<li class='page-item'><a class='page-link' href='#' onclick='getNextData(1);'"."page=1>1</a></li>";
					$pagination.= "<li class='page-item'><a class='page-link' href='#' onclick='getNextData(2);'"."page=2>2</a></li>";
					$pagination.= "...";
					for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
					{
						if ($counter == $page)
							$pagination.= "<span class=\"current\">$counter</span>";
						else
							$pagination.= "<a class='page-link' href='#' onclick='getNextData($counter);'"."page=$counter>$counter</a>";					
					}
					$pagination.= "...";
					$pagination.= "<li class='page-item'><a class='page-link' href='#' onclick='getNextData($lpm1);'"."page=$lpm1>$lpm1</a></li>";
					$pagination.= "<li class='page-item'><a class='page-link' href='#' onclick='getNextData($lastpage);'"."page=$lastpage>$lastpage</a></li>";		
				}
				//close to end; only hide early pages
				else
				{
					$pagination.= "<li class='page-item'><a class='page-link' href='#' onclick='getNextData(1);'"."page=1>1</a></li>";
					$pagination.= "<li class='page-item'><a class='page-link' href='#' onclick='getNextData(2);'"."page=2>2</a></li>";
					$pagination.= "...";
					for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
					{
						if ($counter == $page)
							$pagination.= "<li class='page-item active'><a class=\"page-link\">$counter</a></li>";
						else
							$pagination.= "<li class='page-item'><a class='page-link' href='#' onclick='getNextData($counter);'"."page=$counter>$counter</a></li>";					
					}
				}
			}
			//next button
			if ($page < $counter - 1) 
				$pagination.= "<li class='page-item'><a class='page-link' href='#' onclick='getNextData($next);'"."page=$next><i class='fa fa-angle-right'></i></a></li>";
			else
				$pagination.= "<li class=\"disabled\"></li>";
			$pagination.= "</div>\n";		
		}
		return $pagination;
	}
	
}
?>
