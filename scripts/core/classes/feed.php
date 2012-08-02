<?php
class Feed {
    protected $datetime, $homepage, $title, $subtitle, $feedurl, $feed_email;
	
	/********************/
	/* PUBLIC FUNCTIONS */
	/********************/
	public function __construct($filename=NULL,$typee=NULL) {
		if($filename != NULL) {
			$this->datetime = date("D, d M Y H:i:s");
			$this->title = SITE_NAME;
			$this->subtitle = SUB_TITLE;
			$this->feedurl = HOMEPAGE . $filename;
			$this->feed_email = EM_FEED_ADDRESS;
			$homepage = HOMEPAGE;
			
			// Optional to force a slash at the end of homepage if its not give
			//if(substr($homepage,-1,1) != "/") $homepage = $homepage ."/";
			
			$this->homepage = $homepage;
			
			$fh = fopen($filename,"w");
				$headstr = $this->getFeedHead($typee);
				fwrite($fh, $headstr);
				
				/**
		 		 * Normally you'd append a loop here to run through all the relevant pages / posts / rss items on your site.
				 * You could pass an id, or an object to getFeedInner() as the 3rd paramter for that funciton to use
				 */
				$innerstr = $this->getFeedInner($typee,$fh);
				/**
				 * End of loop
				 */
				
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
		}
		return $str;
	}
	private function getFeedInner($typee,$fh,$obj=NULL) {
		$homepage = $this->homepage;
		/**
		 * Needed in this function:
		 * 	i_title, i_details, i_link, atom_id, i_pubdate
		 * 
		 * Also you may need to escape ascii control characters
		 * $i_details = preg_replace('/[\x00-\x09\x0B\x0C\x0E-\x1F\x7F]/', '', $i_details);
		 */
		$str = "";
		switch($typee) {
			case "rss":
				/*
				 *  example rss date: Sat, 07 Sep 2002 09:42:31 GMT
				 */
	            $str = "
	            <item>
	                <title><![CDATA[$i_title]]></title>
	                <link><![CDATA[" . $homepage . "$i_link]]></link>
	                <description><![CDATA[$i_details]]></description>
	                <pubDate>$i_pubdate GMT</pubDate>
	                <guid><![CDATA[" . $homepage . "$i_link]]></guid>
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
					<title><![CDATA[$i_title]]</title>
				    <link href=\"" . $homepage . "$i_link\" />
				    <id>$atom_id</id>
				    <updated>$atom_pubdate</updated>
				    <summary type=\"html\"><![CDATA[$i_details]]></summary>
	            </entry>
	            ";
				break;
		}
		fwrite($fh, $str);
		/*
		 * loop end
		 */
	}
	private function getFeedFooter($typee) {
		switch($typee) {
			case "wpatom":
	            $str = "</feed>";
	            break;
	        case "sitemap":
	            $str = "</urlset>";
	            break;
			case "rss":
	            $str = "</channel>
	            </rss>";
				break;
		}
		return $str;
	}
}
?>