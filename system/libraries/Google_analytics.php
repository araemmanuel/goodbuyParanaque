<?php
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2017, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CodeIgniter
 * @author	EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (https://ellislab.com/)
 * @copyright	Copyright (c) 2014 - 2017, British Columbia Institute of Technology (http://bcit.ca/)
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	https://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Form Validation Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Track Products
 * @author		Justine Jade Carlos
 * @link		N/A
*/

class CI_Google_Analytics {

	private $analytics;
	private $profile = '175807966';
	public function __construct()
    {
        require_once APPPATH.'/third_party/google_analytics/google-api-php-client/vendor/autoload.php';
    }
	
	public function get_views()
	{
		$this->analytics = $this->initializeAnalytics();
		$this->profile = $this->getFirstProfileId($this->analytics);
		$results = $this->getResults($this->analytics, $this->profile);
		return $this->printResults($results);
	}
	
	private function initializeAnalytics()
	{
	  // Creates and returns the Analytics Reporting service object.

	  // Use the developers console and download your service account
	  // credentials in JSON format. Place them in this directory or
	  // change the key file location if necessary.
	  $KEY_FILE_LOCATION = APPPATH . '/third_party/google_analytics/goodbuyph-215412-3440f3e03f57.json';

	  // Create and configure a new client object.
	  $client = new Google_Client();
	  $client->setApplicationName("Hello Analytics Reporting");
	  $client->setAuthConfig($KEY_FILE_LOCATION);
	  $client->setScopes(['https://www.googleapis.com/auth/analytics.readonly']);
	  $analytics = new Google_Service_Analytics($client);

	  return $analytics;
	}

	private function getFirstProfileId($analytics) {
	  // Get the user's first view (profile) ID.

	  // Get the list of accounts for the authorized user.
	  $accounts = $analytics->management_accounts->listManagementAccounts();

	  if (count($accounts->getItems()) > 0) {
		$items = $accounts->getItems();
		$firstAccountId = $items[0]->getId();

		// Get the list of properties for the authorized user.
		$properties = $analytics->management_webproperties
			->listManagementWebproperties($firstAccountId);

		if (count($properties->getItems()) > 0) {
		  $items = $properties->getItems();
		  $firstPropertyId = $items[0]->getId();

		  // Get the list of views (profiles) for the authorized user.
		  $profiles = $analytics->management_profiles
			  ->listManagementProfiles($firstAccountId, $firstPropertyId);

		  if (count($profiles->getItems()) > 0) {
			$items = $profiles->getItems();

			// Return the first view (profile) ID.
			return $items[0]->getId();

		  } else {
			throw new Exception('No views (profiles) found for this user.');
		  }
		} else {
		  throw new Exception('No properties found for this user.');
		}
	  } else {
		throw new Exception('No accounts found for this user.');
	  }
	}

	private function getResults($analytics, $profileId) {
	  // Calls the Core Reporting API and queries for the number of sessions
	  // for the last seven days.
	  $optParams = array(
			  'dimensions' => 'ga:pagePath',
			  'filters' => 'ga:pagePath=~/customer/view_product',
			  'sort' => '-ga:pageviews'); 

	   return $analytics->data_ga->get(
		   'ga:'.$this->profile,
		   date('Y-m-01'),
		   date('Y-m-t'),
		   'ga:pageviews',
		   $optParams
		);
	}

	private function printResults($results) {
	  // Parses the response from the Core Reporting API and prints
	  // the profile name and total sessions.
	  $views_per_url = array();
	  if (count($results->getRows()) > 0) {

		// Print table rows.
		foreach ($results->getRows() as $row) {
			array_push($views_per_url,array(str_replace("/customer/view_product/","",$row[0]),$row[1]));
		}

	  }
	  return $views_per_url;
	}
}
?>