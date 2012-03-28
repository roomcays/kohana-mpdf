<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Render a view as a PDF.
 *
 * @packge     Kohana-mPDF
 * @author     Woody Gilk <woody.gilk@kohanaphp.com>
 * @author     Sergei Gladkovskiy <smgladkovskiy@gmail.com>
 * @copyright  (c) 2009 Woody Gilk
 * @license    MIT
 */
abstract class View_mPDF_Core extends View {

	/*
	protected static $instance = NULL;

	public static function instance()
	{
		if (View_mPDF::$instance === NULL)
		{
			View_mPDF::$instance = new View_mPDF();
		}

		return View_mPDF::$instance;
	}

	public static function get_instance()
	{
		return View_mPDF::$instance;
	}
	*/
	protected $mpdf = NULL;
	protected $view_file = NULL;

	public function __construct($file = NULL, array $data = NULL)
	{
		parent::__construct($file, $data);
		$this->mpdf = new mPDF('UTF-8', 'A4');
	}

	public static function factory($view_file = NULL, array $data = NULL)
	{
		return new View_MPDF($view_file, $data);
	}

	public function render($view_file = NULL)
	{
		if (empty($view_file)) $view_file = $this->view_file;
		// Render the HTML normally
		$html = parent::render($view_file);
		$this->mpdf->WriteHTML($html);
		// Render the HTML to a PDF
		return $this->mpdf->output();
	}

	public function download($generated_filename, $view_file = NULL)
	{
		if (empty($view_file)) $view_file = $this->view_file;
		// Render the HTML normally
		$html = parent::render($view_file);
		$this->mpdf->WriteHTML($html);
		// Render the HTML to a PDF
		$this->mpdf->output($generated_filename, 'D');
	}

	public function inline($generated_filename, $view_file = NULL)
	{
		if (empty($view_file)) $view_file = $this->view_file;
		// Render the HTML normally
		$html = parent::render($view_file);
		$this->mpdf->WriteHTML($html);
		// Render the HTML to a PDF
		$this->mpdf->output($generated_filename, 'I');
		// Necessary to prevent Kohana from overriding the content-type set inside the previous function - we
		// explictly set it to the correct type here...
		Request::current()->headers[] = 'Content-type: application/pdf';
	}

	public function get_mpdf()
	{
		return $this->mpdf;
	}

} // End View_MPDF
