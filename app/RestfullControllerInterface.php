<?php 

/**
 * Interface for RestfullControllers
 */
interface RestfullControllerInterface
{
	public function get();
	public function store(Request $req): ViewAbstract;
	public function update(Request $req): ViewAbstract;
	public function delete(Request $req): ViewAbstract;
}

?>