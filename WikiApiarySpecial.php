<?php
class SpecialWikiApiary extends SpecialPage {
    function __construct() {
        parent::__construct('HoneyBee');
        wfLoadExtensionMessages('HoneyBee');
    } # function

    function execute( $par ) {
        global $wgUser, $wgRequest, $wgOut, $wgArticle;
    	global $wgServer, $wgScriptPath, $wgScript;

        $this->setHeaders();
        $wgOut->setPageTitle( "WikiApiary" );

        # TODO(dan): Straighten out use of $body and $text in the
        # code below.  However, for now, since some code appends,
        # they need to exist.
        $body = '';
        $text = '';

        # Make sure that the necessary keys are defined in LocalSettings.php
        # Could make this even smarter and match them to a regex if we wished
        if (!$egWikiApiaryID) {
                $body .= <<< END
You must add the WikiApiary site ID to your LocalSettings.php to use this extension.

<pre>
\$egWikiApiaryID = 0;
</pre>
END;
                        $wgOut->addWikiText( $body );
                        return;
                }

                # Make sure the user who is making the request has been given permission to use this extension
                if (!$wgUser->isLoggedIn()) {
                  $wgOut->addWikiText(
                    'You must be logged in to use this page.');
                  return;
                }
                if (!$wgUser->isAllowed('amazon2wiki-user')) {
                        $text = "Your account does not have permission to use this extension. Please add the <code>amazon2wiki-user</code> permission to your group to use this extension.";
                        $wgOut->addWikiText( $text );
                        return;
                }

                # URL parameters
                $confirm = $wgRequest->getText('confirm');
                $asin = $wgRequest->getText('asin');

                # The "front" form is the first one you see
                # TODO(dan): Also have a search box
                $front_form = <<<HTML
<form action="/w/index.php">
<input type="hidden" name="title" value="Special:Amazon2Wiki" />
<input type="text" name="asin" value="$asin" />
<input type="submit" name="submit" value="Submit ASIN or Amazon URL" />
</form>

<h3>Bookmarklet</h3>

<p>This bookmarklet will allow you to go right to the preview screen when you are looking at a book on Amazon.com. Just drag this link to your bookmarks to use it.</p>

<p><a href="javascript:location.href='$wgServer$wgScript?title=Special%3AAmazon2Wiki&asin='+encodeURIComponent(location.href)">Add to Pile</a></p>

<p>or you can create a bookmark with the following Javascript (all in one line).</p>

<pre>
javascript:location.href='$wgServer$wgScript?
title=Special%3AAmazon2Wiki&asin='+encodeURIComponent(location.href)
</pre>
HTML;
                if (!$asin || $confirm == "No") {
                  $wgOut->addHTML( $front_form );
                  return;
                }

                # Use a 10-symbol ASIN or look for it in a URL
                if (strlen($asin) != 10) {
                  $ids = find_amazon_ids($asin);
                  if ($ids) {
                    # take the first we found
                    $asin = $ids[0];
                  } else {
                    $wgOut->addWikiText("I can't parse that as an ASIN (10 symbols, like 'B0053ZHZI2') or an Amazon URL");
                    $wgOut->addHTML($front_form);
                    return;
                  }
                }

                        #$debugstr = print_r($item, true);
                        #$debugstr = print_r($wgRequest, true);
                        $text = <<<TEXT
Would you like to add this book?<br/>

<form action="/w/index.php">
<input type="hidden" name="title" value="Special:Amazon2Wiki" />
<input type="hidden" name="asin" value="$asin" />
<input type="submit" name="confirm" value="Yes" />
<input type="submit" name="confirm" value="No" />
</form>
$pagetitle<br/>

<img src="$imgurl"><br/>
TEXT;
                        $wgOut->addHTML( $text );
                        $wgOut->addWikiText($body);
                }

        } #function
} # class

?>
