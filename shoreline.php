<?php

error_reporting( E_ALL );
ini_set( 'display_errors', '1' );

define( 'MYSQL_HOST', 'localhost' );
define( 'MYSQL_USER', 'root' );
define( 'MYSQL_PASS', 'IsAXGIRa' );
define( 'MYSQL_DB', 'shoreline' );

class ShorelineParser {
	static $parser = null;
	
	static $table_prefix = '';
	
	static $current_way_id = null;
	static $current_relation_id = null;
	static $way_node_order = 0;
	
	static $top_level_tag = null;
	
	public static function init( $osm_file, $table_prefix = '' ) {
		self::$table_prefix = $table_prefix;
		
		mysql_connect( MYSQL_HOST, MYSQL_USER, MYSQL_PASS );
		mysql_select_db( MYSQL_DB );
		
		if ( mysql_result( mysql_query( "SELECT COUNT(*) FROM " . self::$table_prefix . "relation_members" ), 0 ) == 0 ) {
			self::$parser = xml_parser_create();
			xml_set_element_handler( self::$parser, array( 'ShorelineParser', 'element_handler_start' ), array( 'ShorelineParser', 'element_handler_end' ) );
		
			$file_handle = fopen( $osm_file, "r" );
		
			while ( $line = fread( $file_handle, 4096 ) ) {
				xml_parse( self::$parser, $line, feof( $file_handle ) );
			}
		
			fclose( $file_handle );
		}
		
		$lakes = mysql_query( "SELECT * FROM " . self::$table_prefix . "relations" );
		
		$total_shoreline = 0;
		
		while ( $lake = mysql_fetch_assoc( $lakes ) ) {
			$last_way_ref = null;
			$way_ids = array();
			
			if ( ! $lake['shoreline'] ) {
				$lake['shoreline'] = 0;
			
				$members = mysql_query( "SELECT * FROM " . self::$table_prefix . "relation_members WHERE relation_id='" . mysql_real_escape_string( $lake['relation_id'] ) . "'" );
			
				while ( $member = mysql_fetch_assoc( $members ) ) {
					if ( 'way' == $member['type'] ) {
						$way_ids[] = $member['ref'];
						
						$lake['shoreline'] += self::get_way_length( $member['ref'] );
					}
				}
				
				mysql_query( "UPDATE " . self::$table_prefix . "relations SET shoreline='" . mysql_real_escape_string( $lake['shoreline'] ) . "' WHERE relation_id='" . mysql_real_escape_string( $lake['relation_id'] ) . "'" );
			}
			
			if ( ! $lake['name'] && ! empty( $way_ids ) ) {
				$ways = mysql_query( "SELECT name FROM " . self::$table_prefix . "ways WHERE way_id IN ( '" . implode( "','", $way_ids ) . "' ) AND name IS NOT NULL LIMIT 1" );
				
				if ( mysql_num_rows( $ways ) > 0 )
					$lake['name'] = mysql_result( $ways, 0 );
			}
			
			if ( $lake['name'] )
				echo "\t" . $lake['name'] . ': ' . $lake['shoreline'] . "\n";
			
			$total_shoreline += $lake['shoreline'];
		}
		
		// It appears that some lakes aren't given relations, just ways.
		$other_lakes = mysql_query( "SELECT w.* FROM " . self::$table_prefix . "ways w LEFT JOIN " . self::$table_prefix . "relation_members r ON r.ref=w.way_id WHERE r.ref IS NULL" );
		
		while ( $lake = mysql_fetch_assoc( $other_lakes ) ) {
			$last_way_ref = null;
			
			if ( ! $lake['shoreline'] ) {
				$lake['shoreline'] = self::get_way_length( $lake['way_id'] );
				
				mysql_query( "UPDATE " . self::$table_prefix . "ways SET shoreline='" . mysql_real_escape_string( $lake['shoreline'] ) . "' WHERE way_id='" . mysql_real_escape_string( $lake['way_id'] ) . "'" );
			}
			
			if ( $lake['name'] )
				echo "\t" . $lake['name'] . ': ' . $lake['shoreline'] . "\n";
			
			$total_shoreline += $lake['shoreline'];
		}
		
		echo $total_shoreline . " miles.\n";
	}
	
	static public function element_handler_start( $parser, $name, $attribs ) {
		if ( 'NODE' == $name ) {
			self::$top_level_tag = $name;
			
			mysql_query( "INSERT IGNORE INTO " . self::$table_prefix . "nodes SET node_id='" . mysql_real_escape_string( $attribs['ID'] ) . "', lat='" . mysql_real_escape_string( $attribs['LAT'] ) . "', lon='" . mysql_real_escape_string( $attribs['LON'] ) . "'" );
		}
		else if ( 'WAY' == $name ) {
			self::$way_node_order = 0;
			self::$top_level_tag = $name;
			
			self::$current_way_id = $attribs['ID'];
			
			mysql_query( "INSERT IGNORE INTO " . self::$table_prefix . "ways SET way_id='" . mysql_real_escape_string( $attribs['ID'] ) . "'" );
		}
		else if ( 'ND' == $name ) {
			mysql_query( "INSERT IGNORE INTO " . self::$table_prefix . "way_nodes SET way_id='" . mysql_real_escape_string( self::$current_way_id ) . "', node_id='" . mysql_real_escape_string( $attribs['REF'] ) . "', `order`='" . mysql_real_escape_string( self::$way_node_order ) . "'" );
			self::$way_node_order++;
		}
		else if ( 'RELATION' == $name ) {
			self::$top_level_tag = $name;
			
			mysql_query( "INSERT IGNORE INTO " . self::$table_prefix . "relations SET relation_id='" . mysql_real_escape_string( $attribs['ID'] ) . "'" );
			
			self::$current_relation_id = $attribs['ID'];
		}
		else if ( 'MEMBER' == $name ) {
			if ( isset( $attribs['TYPE'] ) ) {
				mysql_query( "INSERT IGNORE INTO " . self::$table_prefix . "relation_members SET relation_id='".mysql_real_escape_string(self::$current_relation_id)."', ref='".mysql_real_escape_string($attribs['REF'])."', type='".mysql_real_escape_string( $attribs['TYPE'])."'" );
			}
		}
		else if ( 'TAG' == $name ) {
			if ( 'name' == $attribs['K'] ) {
				if ( 'RELATION' == self::$top_level_tag ) {
					mysql_query( "UPDATE " . self::$table_prefix . "relations SET name='" . mysql_real_escape_string( $attribs['V'] ) . "' WHERE relation_id='" . mysql_real_escape_string( self::$current_relation_id ) . "'" );
				}
				else if ( 'WAY' == self::$top_level_tag ) {
					mysql_query( "UPDATE " . self::$table_prefix . "ways SET name='" . mysql_real_escape_string( $attribs['V'] ) . "' WHERE way_id='" . mysql_real_escape_string( self::$current_way_id ) . "'" );
				}
			}
		}
	}
	
	static public function element_handler_end( $parser, $name ) {
		if ( 'WAY' == $name ) {
			self::$current_way_id = null;
		}
		else if ( 'RELATION' == $name ) {
			self::$current_relation_id = null;
		}
	}
	
	static public function get_node( $node_id ) {
		$result = mysql_query( "SELECT * FROM " . self::$table_prefix . "nodes WHERE node_id='" . mysql_real_escape_string( $node_id ) . "'" );
		
		while ( $row = mysql_fetch_assoc( $result ) )
			return $row;
		
		return false;
	}
	
	static public function get_way_length( $way_id ) {
		$length = 0;
		$last_node_ref = null;
		
		$result = mysql_query( "SELECT shoreline FROM " . self::$table_prefix . "ways WHERE way_id='" . mysql_real_escape_string( $way_id ) . "'" );

		if ( mysql_num_rows( $result ) > 0 ) {
			if ( $length = mysql_result( $result, 0 ) )
				return $length;
		}
		else {
			echo "way_id missing: " . $way_id . "\n";
		}
		
		$result = mysql_query( "SELECT * FROM " . self::$table_prefix . "way_nodes WHERE way_id='" . mysql_real_escape_string( $way_id ) . "' ORDER BY `order` ASC" );
		$length = 0;
		
		while ( $row = mysql_fetch_assoc( $result ) ) {
			if ( $last_node_ref ) {
				$last_node = self::get_node( $last_node_ref );
				$this_node = self::get_node( $row['node_id'] );
				
				$node_length = self::distance( $last_node['lat'], $last_node['lon'], $this_node['lat'], $this_node['lon'] );
				
				$length += $node_length;
			}
			
			$last_node_ref = $row['node_id'];
		}
		
		if ( $length > 0 )
			mysql_query( "UPDATE " . self::$table_prefix . "ways SET shoreline='" . mysql_real_escape_string( $length ) . "' WHERE way_id='" . mysql_real_escape_string( $way_id ) . "'" );
		
		if ( is_nan( $length ) || ! is_numeric( $length ) ) {
			echo "NAN: " . $length . " (" . $way_id . ")\n";
			die;
		}
		
		return $length;
	}
	
	/**
	 * Calculates distance in miles between two lat/long points.
	 *
	 * @param int $lat1
	 * @param int $lon2
	 * @param int $lat2
	 * @param int $lon2
	 * @return The distance between the two points in miles.
	 */
	static public function distance( $lat1, $lon1, $lat2, $lon2 ) {
		$pi80 = M_PI / 180;
		$lat1 *= $pi80;
		$lon1 *= $pi80;
		$lat2 *= $pi80;
		$lon2 *= $pi80;

		$r = 6372.797; // mean radius of Earth in km
		$dlat = $lat2 - $lat1;
		$dlng = $lon2 - $lon1;
		$a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlng / 2) * sin($dlng / 2);
		$c = 2 * atan2(sqrt($a), sqrt(1 - $a));
		$km = $r * $c;

		return $km * 0.621371192;
	}
}

ShorelineParser::init( $argv[1], $argv[2] );