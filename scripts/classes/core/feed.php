<?php
class Feeds {
    private $datetime, $homepage, $title, $subtitle, $feedurl, $feed_email;
	
	/* PUBLIC FUNCTIONS */
	public function __construct($filename=NULL,$typee=NULL) {
		if($filename != NULL) {
			$this->datetime = date("D, d M Y H:i:s");
			$this->title = SITE_NAME;
			$this->subtitle = SUB_TITLE;
			$this->feedurl = HOMEPAGE . $filename;
			$this->feed_email = EM_FEED_ADDRESS;
			$homepage = HOMEPAGE;
			if(substr($homepage,0,-1) != "/") $homepage = $homepage ."/";
			$this->homepage = $homepage;
			
			$fh = fopen($filename,"w");
				$headstr = $this->getFeedHead($typee);
				fwrite($fh, $headstr);
				$innerstr = $this->getFeedInner($typee,$fh);
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
	}
	private function getFeedInner($typee,$fh) {
		/**
		 * Normally you'd append a loop here to run through all the relevant pages on your site
		 * Needed in loop:
		 * 	i_title, i_details, i_link, atom_id, i_pubdate
		 * 	example atom_id: tag:yourdomain.com,2006-05-02:/archive/post01
		 */
		$str = "";
		switch($typee) {
			case "rss":
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
		            <loc>" . HOMEPAGE . "</loc>
		            <changefreq>daily</changefreq>
		            <priority>0.9</priority>
	           </url>";
	            break;
			case "atom":
				$str = "
	            <entry>
					<title><![CDATA[$i_title]]</title>
				    <link href=\"" . $homepage . "$i_link\" />
				    <id>$atom_id</id>
				    <updated>2003-12-13T18:30:02Z</updated>
				    <summary type=\"html\"><![CDATA[$i_details]]></summary>
	            </entry>
	            ";
				break;
		}
		fwrite($fh, $innerstr);
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