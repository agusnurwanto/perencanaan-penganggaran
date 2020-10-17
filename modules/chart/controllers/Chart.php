<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Chart extends Aksara
{
	private $_table									= 'ta__belanja_rinc';
	private $_primary								= null;
	
	function __construct()
	{
		parent::__construct();
		//$this->set_permission();
		$this->set_theme('backend');
		
	}
	
	public function index()
	{
		$this->set_title('Chart List Demo')
		->set_icon('fa fa-line-chart')
		->set_description
		('
			<form class="form-inline" action="/action_page.php">
				<div class="form-group">
					<label for="periode">
						Periode
					</label>
					<input class="form-control input-sm bordered" role="datepicker" id="periode">
				</div>
				<button type="submit" class="btn btn-default btn-sm">
					<i class="fa fa-check"></i>
					Submit
				</button>
			</form>
		')
		->set_output
		(
			array
			(
				'chart_1'							=> $this->_chart_1(),
				'chart_2'							=> $this->_chart_2(),
				'chart_3'							=> $this->_chart_3(),
				'chart_4'							=> $this->_chart_4(),
				'chart_5'							=> $this->_chart_5(),
				'chart_6'							=> $this->_chart_6()
			)
		)
		->render();
	}
	
	private function _chart_1()
	{
	}
	
	private function _chart_2()
	{
	}
	
	private function _chart_3()
	{
	}
	
	private function _chart_4()
	{
	}
	
	private function _chart_5()
	{
	}
	
	private function _chart_6()
	{
	}
}