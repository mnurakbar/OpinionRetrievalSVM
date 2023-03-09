<?php
 
class database
{
    var $koneksi ;
    var $selectDb;
    var $query ;
    var $result ;
    var $row;
    var $jumlah ;
 
    function database()//constructor
    {
      $this->koneksi = mysql_connect("localhost","root","");
      $this->selectDb = mysql_select_db("ta" , $this->koneksi);
      if ( !$this->selectDb )
      {
        echo "gagal";
      }
    }
 
    function query($query)
    {
      $this->result = mysql_query($query,$this->koneksi);
    }
 
    function tampilkan()
    {
      $this->row = mysql_fetch_array($this->result);
      return $this->row;
    }
 
    function getJumlah()
    {
      $this->jumlah = mysql_num_rows($this->result);
      return $this->jumlah;
    }
 
    function get($table)
    {
      $this->result = mysql_query("SELECT * FROM ".$table);
    }
 
	function insert( $table , $data)
    {
		$this->result = mysql_query("insert into $table values ('','$data[0]', '$data[1]', '$data[2]', '$data[3]','$data[4]')");
	}

	function update($table , $data , $where)
	{
		foreach ( $data as $kolom => $row )
		{
			$set[]= $kolom."='".$row."'" ;
		}
		$set = implode(',',$set);
		$query = "UPDATE ".$table." SET ".$set." WHERE ".$where ;
		$this->query($query);
	}
 
	function delete($id)
	{
		$this->query("DELETE FROM guestbook WHERE id = ".$id);
	}
	
	function links($link, $kata)
	{
		echo "<a href=".$link.">".$kata."</a>";
	}
 
}
?>