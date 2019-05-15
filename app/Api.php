<?php 

/**
* 
*/
class Api
{
	private $interval;
	private static $instance;

	protected $changes = [];
	protected $delete = [];

	private function __construct(){	}

	public static function getInstance(): self
	{
		if (!self::$instance) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Returns all intervals
	 * @return [type] [description]
	 */
	public function get(){
		$results = self::db()->query('SELECT * FROM prices ORDER BY date_start');
		return json_encode($results);
	}

	/**
	 * Creates a new Interval and resolves conflicts
	 * @param  Request
	 * @return [type]
	 */
	public function store(Request $req){
		$this->interval = new Interval([
			'date_start' => $req->data('date_start'),
			'date_end' => $req->data('date_end'),
			'price' => $req->data('price')
		]);

		return json_encode($this->resolve());
	}

	/**
	 * Updates an existing Interval and resolves conflicts
	 * @param  Request
	 * @return [type]
	 */
	public function update(Request $req){
		$this->interval = new Interval([
			'date_start' => $req->data('date_start'),
			'date_end' => $req->data('date_end'),
			'price' => $req->data('price')
		]);


		return json_encode($this->resolve());
	}

	/**
	 * Deletes an existing Interval
	 * @param  Request
	 * @return [type]
	 */
	public function delete(Request $req){
		$res = self::db()->getCollection('SELECT * FROM prices WHERE id=:id ORDER BY date_start',[
			'id' => $req->data('id')
		]);
		$this->interval = $res[0];

		$this->interval->delete();

		return json_encode(['message' => 'success']);
	}

	/**
	 * Clears the intervals table
	 * @return [type]
	 */
	public function clearDB(){
		self::db()->clearDb();
		return json_encode(['message' => 'success']);
	}

	/**
	 * Gets the Database class instance
	 * @return DB
	 */
	public static function db(): DB
	{
		return DB::getInstance();
	}


	/**
	 * @param  Resolves conflicts with the new Interval
	 * @return [type]
	 */
	private function resolve()
	{
		if (!$interval = $this->interval) {
			return ['error' => 'No interval to resolve'];
		}

		if (!$conflicts = $this->getConflicts()) {
			$this->changes[] = $interval;
		}

		$saveNewInterval = true;
		
		$joined = null;

		foreach ($conflicts as $key => $old) {
			
			//Get type of conflict
			$type = $this->interval->typeOfconflict($old);
			
			//Get the resolved conflict
			$fixed = $this->fixConflict($type,$old,$joined,$saveNewInterval);

			if (isset($fixed['joined'])) {
				$joined = $fixed['joined'];
			}

			if (isset($fixed['saveNewInterval'])) {
				$saveNewInterval = $fixed['saveNewInterval'];
			}
			
		}

		if ($saveNewInterval) {
			$this->changes[] = $this->interval;
		}


		try{
			$this->saveChanges();
		}catch(Exception $e){
			return ['Error' => $e->getMessage()];
		}

		return ['message' => 'Success'];
	}

	/**
	 * Fixes a conflict with one Interval to another
	 * @param  String $type
	 * @param  Interval $old
	 * @param  Interval|null $joined
	 * @param  [type] $saveNewInterval
	 * @return Array
	 */
	private function fixConflict(String $type,Interval $old, Interval $joined=null, $saveNewInterval)
	{
		if (!$this->interval) {
			return false;
		}

		switch ($type) {
			case 'merge_left':
				if ($joined) {
					//Update joined interval
					$joined->setDateEnd($old->getDateEnd());
					$this->delete[] = $old;
				}else{
					$old->setDateStart( $this->interval->getDateStart() );
					$this->changes[] = $old;
					$joined = $old;
				}
				$saveNewInterval = false;
				break;

			case 'merge_right':
				$old->setDateEnd( $this->interval->getDateEnd() );
				$this->changes[] = $old;

				$saveNewInterval = false;
				$joined = $old;
				break;

			case 'intersects_left':
				//Update old interval
				$old->setDateStart( $this->interval->getAfterDate() );
				$this->changes[] = $old;
				break;

			case 'intersects_right':
				//Update old interval
				$old->setDateEnd( $this->interval->getBeforeDate() );
				$this->changes[] = $old;
				break;

			case 'intersects_left_merge':
				if ($joined) {
					//Update joined interval
					$joined->setDateEnd( $old->getDateEnd() );
					$this->delete[] = $old;
				}else{
					$old->setDateStart( $this->interval->getDateStart() );
					$this->changes[] = $old;
					$joined = $old;
				}
				$saveNewInterval = false;
				break;

			case 'intersects_right_merge':
				$old->setDateEnd( $this->interval->getDateEnd() );
				$this->changes[] = $old;
				$joined = $old;
				$saveNewInterval = false;

				break;

			case 'new_inside_old':
				if ($joined) {
					$new = $joined;
				}else{
					$new = $this->interval;
				}

				if($old->getDateEnd() > $new->getDateEnd()){
					$newInterval = new Interval([
						'date_start' => $new->getAfterDate(),
						'date_end' => $old->getDateEnd(),
						'price' => $old->getPrice()
					]);
					if ($joined) {
						$newInterval->setId($old->getId());
					}
					$this->changes[] = $newInterval;
				}
				if ($old->getDateStart() < $new->getDateStart()) {
					$old->setDateEnd( $new->getBeforeDate() );
					$this->changes[] = $old;
				}
				if ($joined && $old->getDateStart() == $new->getDateStart()) {
					//Update old with new interval info
					$old->setDateEnd( $new->getDateEnd() );
					$old->setPrice( $new->getPrice() );
					$this->changes[] = $old;
					$saveNewInterval = false;
				}else if($old->getDateStart() == $new->getDateStart() && $old->getDateEnd() == $new->getDateEnd()){
					$this->delete[] = $old;
				}

				//old inside joined
				if ($joined && $new->getDateStart() <= $old->getDateStart() && $new->getDateEnd() >= $old->getDateEnd()) {
					$this->delete[] = $old;
				}

				break;

			case 'new_inside_old_merge':
				$saveNewInterval = false;
				break;

			case 'old_inside_new':
				if ($joined) {
					//Delete Old
					$this->delete[] = $old;
				}else{
					$old->setDateStart( $this->interval->getDateStart() );
					$old->setDateEnd( $this->interval->getDateEnd() );
					$old->setPrice($this->interval->getPrice());
					$this->changes[] = $old;
					$joined = $old;
				}
				$saveNewInterval = false;
				break;
		}

		return [
			'joined' => $joined,
			'saveNewInterval' => $saveNewInterval
		];
	}

	/**
	 * Saves changes originated from resolving conflicts
	 * @return [type]
	 */
	private function saveChanges()
	{
		foreach ($this->changes as $interval) {
			$interval->save();
		}

		foreach ($this->delete as $interval) {
			$interval->delete();
		}
	}

	
	/**
	 * Gets the Intervals on DB in conflict with the new one
	 * @return array
	 */
	public function getConflicts(): array
	{
		$conflicts = self::db()->getCollection("SELECT * FROM ".$this->interval->getTableName()." WHERE (
		(date_end=:before_date and price=:price) OR 
		(date_start=:after_date and price=:price) OR 
		(date_start >=:date_start and date_start <=:date_end) OR 
		(date_end >=:date_start and date_end <=:date_end ) OR 
		(date_start <:date_start and date_end >:date_end )) 
		ORDER BY date_start",[
			'before_date' => $this->interval->getBeforeDate(),
			'after_date' => $this->interval->getAfterDate(),
			'price' => $this->interval->getPrice(),
			'date_start' => $this->interval->getDateStart(),
			'date_end' => $this->interval->getDateEnd(),
		]);


		return $conflicts;
	}

}

?>