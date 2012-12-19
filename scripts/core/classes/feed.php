<?php
class Feed {
    protected $datetime, $homepage, $title, $subtitle, $feedurl, $feed_email;
	
	/********************/
	/* PUBLIC FUNCTIONS */
	/********************/
	public function __construct($filename=NULL,$typee=NULL,$localLocation=NULL) {
		if($filename != NULL) {
			if(empty($localLocation)) $localLocation = $filename;
			$this->datetime = date("D, d M Y H:i:s");
			$this->title = SITE_NAME;
			$this->subtitle = SUB_TITLE;
			$this->feedurl = HOMEPAGE . $filename;
			$this->feed_email = EM_FEED_ADDRESS;
			$homepage = HOMEPAGE;
			
			// Optional to force a slash at the end of homepage if its not give
			//if(substr($homepage,-1,1) != "/") $homepage = $homepage ."/";
			
			$this->homepage = $homepage;
			
			$fh = fopen($localLocation,"w");
				$headstr = $this->getFeedHead($typee);
				fwrite($fh, $headstr);
				
				$arr = array('orderby' => 'jid desc');
				$jobs = getJobs($arr);				
				foreach($jobs as $job) {
					$innerstr = $this->getFeedInner($typee,$fh,$job);
				}
				
				$footerstr = $this->getFeedFooter($typee);
				fwrite($fh, $footerstr);   
    		fclose($fh);
		}
	}
    
	protected function getFeedHead($typee) {
		$datetime = $this->datetime;
		$homepage = $this->homepage;
		$title = $this->title;
		$subtitle = $this->subtitle;
		$feedurl = $this->feedurl;
		$feed_email = $this->feed_email;
		switch($typee) {
			case "rss":
	            $str = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
	            <rss version=\"2.0\">
	              <channel>
	                <title><![CDATA[$title]]></title>
	                <link><![CDATA[$homepage]]></link>
	                <description><![CDATA[$subtitle]]></description>
	                <language>en-us</language>
	                <pubDate>$datetime</pubDate>
	                <lastBuildDate>$datetime</lastBuildDate>
	                <docs><![CDATA[$feedurl]]></docs>
	                <generator>$title</generator>
	                <managingEditor>$feed_email</managingEditor>
	                <webMaster>$feed_email</webMaster>
	            ";
	            break;
	        case "sitemap":
	            $str = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
	            <urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">
	            ";
	            break;
	        case "atom":
	            $str = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
	            <feed
	              xmlns=\"http://www.w3.org/2005/Atom\"
	              xmlns:thr=\"http://purl.org/syndication/thread/1.0\"
	              xml:lang=\"en\"
	              xml:base=\"$homepage\"
	            >
	                <title type=\"text\"><![CDATA[$title]]></title>
	                <subtitle type=\"text\"><![CDATA[$subtitle]]></subtitle>
	
	                <updated>$datetime</updated>
	                <generator uri=\"$homepage\" version=\"2.2.1\"></generator>
	
	                <link rel=\"alternate\" type=\"text/html\" href=\"$homepage\" />
	                <id><![CDATA[$feedurl]]></id>
	                <link rel=\"self\" type=\"application/atom+xml\" href=\"$feedurl\" />";
	            break;
	        case "googlebase":
	            $str = "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>
	            <rss version=\"2.0\" xmlns:g=\"http://base.google.com/ns/1.0\" xmlns:c=\"http://base.google.com/ns/1.0\">
	            <channel>
	                <title>$title</title>
	                <link><![CDATA[$homepage]]></link>
	                <description><![CDATA[$title]]></description>\"
	                ";
	            break;
				
			case "trovit":
	            $str = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
	            <trovit>";
	            break;
	        case "jobisjob":
	            $str = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
	            <trovit>";
	            break;
	        case "simplyhired":
	            $str = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
	            <jobs>";
	            break;
	        case "twitter_job_search":
	            $str = "<?xml version=\"1.0\"?>
	            <jobs>
	              <publisher-name>$title</publisher-name>
	              <publisher-url>$homepage</publisher-url>
	            ";
	            break;
	        case "indeed":
	            $str = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
	            <source>
	            <publisher>$title</publisher>
	            <publisherurl><![CDATA[$homepage]]></publisherurl>
	            <lastBuildDate>$datetime</lastBuildDate>";
	            break;
			case "regional":
				$str = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
						<source>
							<publisher>$title</publisher>
							<publisherurl><![CDATA[$homepage]]></publisherurl>
							<lastBuildDate>$datetime</lastBuildDate>";
	            break;
			case "oodle":
				$str = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
						<listings>";
				break;
		}
		return $str;
	}
	protected function getFeedInner($typee,$fh,$job=NULL) {
		$homepage = $this->homepage;
		$str = "";
		switch($typee) {
			case "rss":
				/*
				 *  example rss date: Sat, 07 Sep 2002 09:42:31 GMT
				 */
	            $str = "
	            <item>
	                <title><![CDATA[" . $job->j_title . "]]></title>
	                <link><![CDATA[" . $homepage . $job->link . "]]></link>
	                <description><![CDATA[" . $job->j_details . "]]></description>
	                <pubDate>" . date("D, d M Y H:i:s",strtotime($job->j_startdate)) . " GMT</pubDate>
	                <guid><![CDATA[" . $homepage .  $job->link . "]]></guid>
	            </item>";
	            break;
	        case "sitemap":
	            $str = "
				<url>
		            <loc>" . $homepage . "</loc>
		            <changefreq>daily</changefreq>
		            <priority>0.9</priority>
	           </url>";
	            break;
			case "atom":
				/*
				 *  example atom updated date: 2012-12-13T18:30:02Z
		 		 * 	example atom_id: tag:yourdomain.com,2006-05-02:/archive/post01
				 */
				$str = "
	            <entry>
					<title><![CDATA[" . $job->j_title . "]]</title>
				    <link href=\"" . $homepage . $job->link . "\" />
				    <id>tag:" . $homepage . "," . $job->link . "</id>
				    <updated>" . date("Y-m-d\TH:i:s",strtotime($job->j_startdate)) . "</updated>
				    <summary type=\"html\"><![CDATA[" . $job->j_details . "]]></summary>
	            </entry>
	            ";
				break;
			case "googlebase":
	            $str = "
	            <item>
	                <title><![CDATA[" . $job->j_title . "]]></title>
	                <link><![CDATA[" . $homepage . "" . $job->link . "]]></link>
	                <description><![CDATA[" . $job->j_details . "]]></description>
	                <g:id><![CDATA[" . $job->jid . "]]></g:id>
	                <g:employer><![CDATA[" . $job->getCompany()->cm_name . "]]></g:employer>
	                <g:job_function><![CDATA[" . $job->j_title . "]]></g:job_function>
	                <g:job_industry><![CDATA[" . $job->categoryNames() . "]]></g:job_industry>
	                <g:location><![CDATA[" . $job->getPublicAddress() . "]]></g:location>
	                <g:salary><![CDATA[" . $job->currency . " " . $job->salary . "]]></g:salary>
	                <g:job_type><![CDATA[" . $job->jobtype . "]]></g:job_type>
	                <c:company type=\"string\"><![CDATA[" . $job->getCompany()->cm_name . "]]></c:company>
	                <c:city type=\"string\"><![CDATA[" . $job->town . "]]></c:city>
	                <c:state type=\"string\"><![CDATA[" . $job->county . "]]></c:state>
	                <c:postalcode type=\"string\"><![CDATA[" . $job->postcode . "]]></c:postalcode>
	            </item>
	            ";
	            break;
	        case "trovit":
	            $str = "
	            <ad>
	                <id><![CDATA[" . $job->jid . "]]></id>
	                <title><![CDATA[" . $job->j_title . "]]></title>
	                <url><![CDATA[" . $homepage . "" . $job->link . "]]></url>
	
	                <content><![CDATA[" . $job->j_details . "]]></content>
	
	                <city><![CDATA[" . $job->town . "]]></city>
	                <region><![CDATA[" . $job->county . "]]></region>
	                <postcode><![CDATA[" . $job->postcode . "]]></postcode>
	
	                <salary><![CDATA[" . $job->currency . " " . $job->salary . "]]></salary>
	                <salary_numeric><![CDATA[" . $job->currency . " " . $job->salary . "]]></salary_numeric>
	                <contract><![CDATA[" . $job->j_jobtype . "]]></contract>
	                <company><![CDATA[" . $job->getCompany()->cm_name . "]]></company>
	
	                <experience><![CDATA[]]></experience>
	                <requirements><![CDATA[]]></requirements>
	                <category><![CDATA[" . $job->categoryNames() . "]]></category>
	                <date><![CDATA[" . $job->j_startdate . "]]></date>
	            </ad>
	            ";
	            break;
	        case "jobisjob":
	            $str = "
	            <ad>
	                <id><![CDATA[" . $job->jid . "]]></id>
	                <title><![CDATA[" . $job->j_title . "]]></title>
	                <url><![CDATA[" . $homepage . "" . $job->link . "]]></url>
	
	                <content><![CDATA[" . $job->j_details . "]]></content>
	
	                <city><![CDATA[" . $job->town . "]]></city>
	                <region><![CDATA[" . $job->county . "]]></region>
	                <postcode><![CDATA[" . $job->postcode . "]]></postcode>
	
	                <salary><![CDATA[" . $job->currency . " " . $job->salary . "]]></salary>
	                <salary_numeric><![CDATA[" . $job->currency . " " . $job->salary . "]]></salary_numeric>
	                <contract><![CDATA[" . $job->jobtype . "]]></contract>
	                <company><![CDATA[" . $job->getCompany()->cm_name . "]]></company>
	
	                <experience><![CDATA[]]></experience>
	                <requirements><![CDATA[]]></requirements>
	                <category><![CDATA[" . $job->categoryNames() . "]]></category>
	                <date><![CDATA[" . $job->j_startdate . "]]></date>
	            </ad>
	            ";
	            break;
	        case "simplyhired":
	            $str = "
	            <job>
	                <title><![CDATA[" . $job->j_title . "]]></title>
	                <job-code>" . $job->jid . "</job-code>
	                <job-board-name>" . SITE_NAME . "</job-board-name>
	                <job-board-url><![CDATA[" . $homepage . "]]></job-board-url>
	                <detail-url><![CDATA[" . $homepage . "" . $job->link . "]]></detail-url>
	                <apply-url/>
	                <job-category><![CDATA[" . $job->categoryNames() . "]]></job-category>
	
	                <description>
	                    <summary><![CDATA[" . $job->j_details . "]]></summary>
	                    <required-skills><![CDATA[]]></required-skills>
	                    <required-education/>
	                    <required-experience/>
	                    
	                    <full-time><![CDATA[]]></full-time>
	                    <part-time><![CDATA[]]></part-time>
	                    <flex-time/>
	                    <internship><![CDATA[]]></internship>
	                    <volunteer/>
	                    <exempt/>
	                    <contract><![CDATA[]]></contract>
	                    <permanent/>
	                    <temporary><![CDATA[]]></temporary>
	                    <telecommute/>
	                </description>
	
	                <compensation>
	                    <salary-range/>
	                    <salary-amount><![CDATA[" . $job->currency . " " . $job->salary . "]]></salary-amount>
	                    <salary-currency></salary-currency>
	                    <benefits/>
	                </compensation>
	
	                <posted-date>" . $job->j_startdate . "</posted-date>
	                <close-date>" . $job->j_expirydate . "</close-date>
	
	                <location>
	                    <address><![CDATA[" . $job->getPublicAddress() . "]]></address>
	                    <city><![CDATA[" . $job->town . "]]></city>
	                    <state><![CDATA[" . $job->county . "]]></state>
	                    <zip><![CDATA[" . $job->postcode . "]]></zip>
	                    <country><![CDATA[" . $job->country . "]]></country>
	                    <area-code/>
	                </location>
	
	                <contact>
	                    <name><![CDATA[" . $job->contact_name . "]]></name>
	                    <email>" . $job->contact_email . "</email>
	                    <hiring-manager-name/>
	                    <hiring-manager-email/>
	                    <phone></phone>
	                    <fax/>
	                </contact>
	
	                <company>
	                    <name><![CDATA[" . $job->getCompany()->cm_name . "]]></name>
	                    <description><![CDATA[" . $job->getCompany()->cm_details . "]]></description>
	                    <industry/>
	                    <url><![CDATA[" . $homepage .  $job->getCompany()->link . "]]></url>
	                </company>
	            </job>
	            ";
	            break;
	        case "twitter_job_search":
	            $str = "
	            <job>
	                <id><![CDATA[" . $job->jid . "]]></id>
	                <date><![CDATA[" . $job->j_startdate . "]]></date>
	                <title><![CDATA[" . $job->j_title . "]]></title>
	                <company><![CDATA[" . $job->getCompany()->cm_name . "]]></company>
	                <url><![CDATA[" . $homepage . "" . $job->link . "]]></url>
	                <salary><![CDATA[" . $job->currency . " " . $job->salary . "]]></salary>
	                <jobtype><![CDATA[" . $job->jobtype . "]]></jobtype>
	                <education></education>
	                <experience></experience>
	                <location><![CDATA[" . $job->getPublicAddress() . "]]></location>
	                <postcode><![CDATA[" . $job->postcode . "]]></postcode>
	                <description><![CDATA[" . $job->j_details . "]]></description>
	                <category><![CDATA[" . $job->categoryNames() . "]]></category>
	            </job>
	            ";
	            break;
	        case "indeed":
	            $str = "<job>
		            <title><![CDATA[" . $job->j_title . "]]></title>
		            <date><![CDATA[" . $job->j_startdate . "]]></date>
		            <referencenumber><![CDATA[" . $job->jid . "]]></referencenumber>
		            <url><![CDATA[" . $homepage . "" . $job->link . "]]></url>
		            <company><![CDATA[" . $job->getCompany()->cm_name . "]]></company>
		            <city><![CDATA[" . $job->town . "]]></city>
		            <state><![CDATA[" . $job->county . "]]></state>
		            <country><![CDATA[" . $job->country . "]]></country>
		            <postalcode><![CDATA[" . $job->postcode . "]]></postalcode>
		            <description><![CDATA[" . $job->j_details . "]]></description>
		            <salary><![CDATA[" . $job->currency . " " . $job->salary . "]]></salary>
		            <education><![CDATA[]]></education>
		            <jobtype><![CDATA[" . $job->jobtype . "]]></jobtype>
		            <category><![CDATA[" . $job->categoryNames() . "]]></category>
		            <experience><![CDATA[]]></experience>
	            </job>
	            ";
	            break;
			case "regional":
				$str = "<job>
				    <title><![CDATA[" . $job->j_title . "]]></title>
				    <date><![CDATA[" . $job->j_startdate . "]]></date>
				    <referencenumber><![CDATA[" . $job->jid . "]]></referencenumber>
				    <url><![CDATA[" . $homepage . "" . $job->link . "]]></url>
				    <city><![CDATA[" . $job->town . "]]></city>
				    <country><![CDATA[" . $job->country . "]]></country>
				    <description><![CDATA[" . $job->j_details . "]]></description>
				    <salary><![CDATA[" . $job->currency . " " . $job->salary . "]]></salary>
				    <jobtype><![CDATA[" . $job->jobtype . "]]></jobtype>
				    <category><![CDATA[" . $job->categoryNames() . "]]></category>
					<email><![CDATA[" . $job->contact_email . "]]></email>
				</job>";
				break;
			case "oodle":
				$str = "<listing>
					<category>Recruiter Jobs</category>
					<description><![CDATA[" . $job->j_details . "]]></description>
					<id>" . $job->jid . "</id>
					<title><![CDATA[" . $job->j_title . "]]></title>
					<url><![CDATA[" . $homepage . "" . $job->link . "]]></url>
					<city><![CDATA[" . $job->town . "]]></city>
					<country><![CDATA[" . $job->country . "]]></country>
					<zip_code><![CDATA[" . $job->postcode . "]]></zip_code>
					<company><![CDATA[" . $job->getCompany()->cm_name . "]]></company>
					<create_time>" . $job->j_startdate . "</create_time>
					<industry><![CDATA[" . $job->categoryNames() . "]]></industry>
					<salary><![CDATA[" . $job->currency . " " . $job->salary . "]]></salary>
					<seller_email>" . $job->contact_email . "</seller_email>
				</listing>";
				break;
		}
		fwrite($fh, $str);
	}
	protected function getFeedFooter($typee) {
		$str = "";
		switch($typee) {
			case "atom":
	            $str = "
	            </feed>";
	            break;
	        case "sitemap":
	            $str = "
	            </urlset>";
	            break;
			case "rss":
	            $str = "
	            </channel>
	            </rss>";
				break;
		    case "trovit":
		        $str = "
		        </trovit>";
		        break;
		    case "jobisjob":
		        $str = "
		        </trovit>";
		        break;
		    case "simplyhired":
		        $str = "
		        </jobs>";
		        break;
		    case "twitter_job_search":
		        $str = "
		        </jobs>";
		        break;
		    case "indeed":
		        $str = "
		        </source>";
		        break;
			case "regional":
				$str = "
				</source>";
				break;
			case "oodle":
				$str = "
				</listings>";
				break;
		    default:
		        $str = "
		        </channel>
		        </rss>";
		        break;
		}
		return $str;
	}
}
?>