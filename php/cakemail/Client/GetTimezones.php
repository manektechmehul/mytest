<?php

/**
 * Client/GetTimezones.php
 *
 * Copyright (c) 2002-2009 The Code Kitchen Inc.
 * 
 * Software designer & developer: 
 *  George Alexandru Dorobantu <gdalex@gmail.com>
 */

	unset($GLOBALS["error_code"]);
	unset($GLOBALS["error_message"]);

	try {
		$xml = new cake_Xml();

		$xml->init();
		$xml->add_class("ClassClient", $locale);
		$xml->add_method("GetTimezones");
		$xml->close_method();
		$xml->close_class();
		$xml->close();

		$xml->exec_xml();
		$r = $xml->get_response();

	} catch (Exception $e) {
		$GLOBALS["error_code"] = $e->getCode();
		$GLOBALS["error_message"] = $e->getMessage();
		throw new Exception($GLOBALS["error_message"], $GLOBALS["error_code"]);
	}

	$res = array();
	$res["timezones"] = array();
	if (isset($r[0]["class"][0]["method"])) {
		$i = 0;
		foreach ($r[0]["class"][0]["method"] as $result) {
			switch ($result["name"]) {
			case "error_code":
				$GLOBALS["error_code"] = $result["value"];
				break;	
			case "error_message":
				$GLOBALS["error_message"] = $result["value"];
				break;
			case "timezone":
				foreach ($result["timezone"] as $element) {
					$res["timezones"][$i][$element["name"]] = $element["value"];
				}
				$i++;
			}
		}
	}

	if (isset($GLOBALS["error_code"]) || isset($GLOBALS["error_message"])) {
		throw new Exception($GLOBALS["error_message"], $GLOBALS["error_code"]);
	}

	return $res;

?>
