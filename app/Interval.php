<?php


/**
* 
*/
class Interval
{

	private $id;
	private $date_start;
	private $date_end;
	private $price;
	private $created_at;


	private $table = "prices";
	
	function __construct(Array $interval)
	{
		if (isset($interval['id'])) {
			$this->setId($interval['id']);
		}

		if (isset($interval['date_start'])) {
			$this->setDateStart($interval['date_start']);
		}

		if (isset($interval['date_end'])) {
			$this->setDateEnd($interval['date_end']);
		}

		if (isset($interval['price'])) {

			$this->setPrice(floatval($interval['price']));
		}

		if (isset($interval['created_at'])) {
			$this->created_at = $interval['created_at'];
		}

		//Check if valid interval
		if (!$this->validInterval()) {
			throw new Exception("Starting date must be lower than End date", 1);	
		}
	}

	public function getTableName(){
		return $this->table;
	}

	/**
	 * Returns the DB instance
	 * @return DB
	 */
	public static function db(): DB
	{
		return DB::getInstance();
	}

	public function getId(){
		return $this->id;
	}

	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function getDateStart()
	{
		return $this->date_start;
	}

	public function setDateStart($date)
	{
		if ($this->validateDate($date)) {
			$this->date_start = $date;
		}else{
			throw new Exception('Invalid Date: ');
		}

	}

	public function getDateEnd()
	{
		return $this->date_end;
	}

	public function setDateEnd($date)
	{
		if ($this->validateDate($date)) {
			$this->date_end = $date;
		}else{
			throw new Exception("Invalid Date");
		}
	}

	public function getPrice()
	{
		return $this->price;
	}

	public function setPrice(Float $price)
	{
		$this->price = $price;
	}

	/**
	 * Returns true if date is valid according to $format
	 * @param  String
	 * @param  string
	 * @return Bool
	 */
	public function validateDate(String $date, $format = 'Y-m-d')
	{
		$d = DateTime::createFromFormat($format, $date);
		return $d && $d->format($format) === $date;
	}

	/**
	 * Returns true if Interval dates are valid
	 * @return Bool
	 */
	private function validInterval()
	{
		if ($this->date_start <= $this->date_end) {
			return true;
		}else{
			return false;
		}
	}

	/**
	 * Saves the interval
	 * @return [type]
	 */
	public function save(){
		try{
			if ($this->id) {
				self::db()->execute("UPDATE ".$this->table." SET date_start=:date_start,date_end=:date_end,price=:price WHERE id=:id",[
					'date_start' => $this->date_start,
					'date_end' => $this->date_end,
					'price' => $this->price,
					'id' => $this->id
				]);
			}else{
				$id = self::db()->execute("INSERT INTO ".$this->table." (date_start,date_end,price) VALUES (:date_start,:date_end,:price)",[
					'date_start' => $this->date_start,
					'date_end' => $this->date_end,
					'price' => $this->price,
				]);
				$this->id = $id;
			}
		}catch(Exception $e){
			echo 'Error: '.$e->getMessage();
		}
	}

	/**
	 * Deletes the interval
	 * @return [type]
	 */
	public function delete(){
		try{
			self::db()->execute("DELETE FROM ".$this->table." WHERE id=:id",[
				'id' => $this->id
			]);
			
			return true;
		}catch(Exception $e){
			echo 'Error: '.$e->getMessage();
		}
	}

	/**
	 * Returns the date Before the interval
	 * @return string
	 */
	public function getBeforeDate(){
		return date('Y-m-d',strtotime($this->date_start. ' -1 day'));
	}

	/**
	 * Returns the date After the interval
	 * @return string
	 */
	public function getAfterDate(){
		return date('Y-m-d',strtotime($this->date_end. ' +1 day'));
	}


	/**
	 * Returns the type of conflict with one interval to another
	 * @param  Interval
	 * @return string
	 */
	public function typeOfconflict(Interval $interval){
		$new_date_start = $this->date_start;
		$new_date_end = $this->date_end;
		$new_price = $this->price;

		$old_date_start = $interval->date_start;
		$old_date_end = $interval->date_end;
		$old_price = $interval->price;

		//Merge Left
		if ($new_date_end == $old_date_start && $new_price == $old_price) {
			return 'merge_left';
		}

		//Merge Right
		if ($new_date_start == $old_date_end && $new_price == $old_price) {
			return 'merge_right';
		}
		
		//Intersects Left
		if ($new_date_start < $old_date_start && $new_date_end < $old_date_end && $new_price != $old_price) {
			return 'intersects_left';
		}

		//Intersects Right
		if ($new_date_start > $old_date_start && $new_date_end > $old_date_end && $new_price != $old_price) {
			return 'intersects_right';
		}
		
		//Intersects Left & merge
		if ($new_date_start < $old_date_start && $new_date_end < $old_date_end && $new_price == $old_price) {
			return 'intersects_left_merge';
		}

		//Intersects Right & merge
		if ($new_date_start > $old_date_start && $new_date_end > $old_date_end && $new_price == $old_price) {
			return 'intersects_right_merge';
		}

		//New inside Old
		if ($new_date_start >= $old_date_start && $new_date_end <= $old_date_end && $new_price != $old_price) {
			return 'new_inside_old';
		}

		//New inside Old & merge
		if ($new_date_start >= $old_date_start && $new_date_end <= $old_date_end && $new_price == $old_price) {
			return 'new_inside_old_merge';
		}

		//Old inside new
		if ($old_date_start >= $new_date_start && $old_date_end <= $new_date_end) {
			return 'old_inside_new';
		}
	}

}

?>