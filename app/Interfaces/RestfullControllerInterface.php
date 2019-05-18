<?php 
namespace App\Interfaces;
use App\Views\ViewAbstract;
use App\Request;

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