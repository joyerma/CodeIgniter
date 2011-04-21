<?php

require BASEPATH.'libraries/Parser.php';

class Parser_test extends CI_TestCase
{
	
	public function setUp()
	{
		$obj = new StdClass;
		$obj->parser = new CI_Parser();
		
		$this->ci_instance($obj);
		
		$this->parser = $obj->parser;
	}
	// --------------------------------------------------------------------
	
	public function testSetDelimiters()
	{
		// Make sure default delimiters are there
		$this->assertEquals('{', $this->parser->l_delim);
		$this->assertEquals('}', $this->parser->r_delim);
		
		// Change them to square brackets
		$this->parser->set_delimiters('[', ']');
		
		// Make sure they changed
		$this->assertEquals('[', $this->parser->l_delim);
		$this->assertEquals(']', $this->parser->r_delim);
		
		// Reset them
		$this->parser->set_delimiters();
		
		// Make sure default delimiters are there
		$this->assertEquals('{', $this->parser->l_delim);
		$this->assertEquals('}', $this->parser->r_delim);
	}
	
	// --------------------------------------------------------------------
	
	public function testParseSimpleString()
	{
		$data = array(
			'title' => 'Page Title',
			'body' => 'Lorem ipsum dolor sit amet.'
		);
		
		$template = "{title}\n{body}";
		
		$result = implode("\n", $data);
		
		$this->assertEquals($result, $this->parser->parse_string($template, $data, TRUE));
	}
	
	// --------------------------------------------------------------------
	
	public function testParse()
	{
		$this->_parse_no_template();
		$this->_parse_var_pair();
		$this->_mismatched_var_pair();
	}
	
	// --------------------------------------------------------------------
	
	private function _parse_no_template()
	{
		$this->assertFalse($this->parser->parse_string('', '', TRUE));
	}
	
	// --------------------------------------------------------------------
	
	private function _parse_var_pair()
	{
		$data = array(
			'title'		=> 'Super Heroes',
			'powers'	=> array(
					array(
						'invisibility'	=> 'yes',
						'flying'		=> 'no'),
			)
		);
		
		$template = "{title}\n{powers}{invisibility}\n{flying}{/powers}";
		
		$result = "Super Heroes\nyes\nno";
		
		$this->assertEquals($result, $this->parser->parse_string($template, $data, TRUE));	
	}
	
	// --------------------------------------------------------------------
	
	private function _mismatched_var_pair()
	{
		$data = array(
			'title'		=> 'Super Heroes',
			'powers'	=> array(
					array(
						'invisibility'	=> 'yes',
						'flying'		=> 'no'),
			)
		);
		
		$template = "{title}\n{powers}{invisibility}\n{flying}";
		
		$result = "Super Heroes\n{powers}{invisibility}\n{flying}";
		
		$this->assertEquals($result, $this->parser->parse_string($template, $data, TRUE));			
	}

	// --------------------------------------------------------------------
}