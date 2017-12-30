<?php

// Things to notice:
// You need to add your recommendations for the video sharing component to this script
// You should use client-side code (i.e., HTML5/JavaScript/jQuery) to help you organise and present your analysis
// For example, using tables, bullet point lists, images, hyperlinking to relevant materials, etc.

// execute the header script:
require_once "header.php";

if (!isset($_SESSION['loggedInSkeleton']))
{
	// user isn't logged in, display a message saying they must be:
	echo "You must be logged in to view this page.<br>";
}
else
{
	echo <<<_END

	<div class="sharing">
		<h1>Video Sharing</h1>
		<h2>Introduction</h2>
		<p>I have identified Plyr, Afterglow and Video.js as three JavaScript video players that will form the basis of this comparison and ultimately, my suggestion as to which is most suitable for video implementation in the global feed. To start with, I think it is key to get some background to each player before looking at criteria.</p>

		<h3>Plyr</h3>
		<p>Plyr is a lightweight HTML5 media player with accessibility, compatibility and customisation at the forefront of its design. Plyr supports local media as well as YouTube, Vimeo and streaming formats for added extensibility. The player is still being actively developed by a small community of contributors through GitHub and emphasises easy to use documentation and simple event handling.</p>

		<h3>Afterglow</h3>
		<p>Similarly, Afterglow is a HTML5 media player with fast and functional integration in mind. Afterglow is dependent on the Video.js player framework which is covered of its own accord later. Notably, as well as comprehensive and well-presented documentation, their website features a community section where developers can easily access support and submit bug reports.</p>

		<h3>Video.js</h3>
		<p>The Video.js player has amassed a number of prominent users including the likes Microsoft, Instagram, Twitter and Dropbox. Video.js is a larger project than the aforementioned with corporate partner Brightcove contributing to the employment of developers and investment in the project. The player can be seen in use on over 400,000 websites worldwide and supports local media as well as YouTube and Vimeo with additional plugins.</p>

		<hr>

		<h2>Performance</h2>
		<p>A key aspect when choosing any library for development is its speed and performance. By using a lightweight player this could reduce bandwidth usage in loading the library and in turn, reduce loading times for users. To measure this, I did some simple benchmarking by using each library to load the same 10mb video into a blank page in identical running environments with a cleared cache. I performed three page loads for each library and took the averages using Google Chrome's network monitoring function.</p>

		<table>
			<thead>
				<tr>
					<td>Library</td>
					<td>Load #1 (ms)</td>
					<td>Load #2 (ms)</td>
					<td>Load #3 (ms)</td>
					<td>Average Load (ms)</td>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>Plyr</td>
					<td>393</td>
					<td>392</td>
					<td>396</td>
					<td>394</td>
				</tr>
				<tr>
					<td>Afterglow</td>
					<td>598</td>
					<td>614</td>
					<td>587</td>
					<td>600</td>
				</tr>
				<tr>
					<td>Video.js</td>
					<td>497</td>
					<td>516</td>
					<td>485</td>
					<td>499</td>
				</tr>
			</tbody>
		</table>

		<p>This would suggest that the fastest library of those put forward is Plyr. Expectedly, Afterglow builds upon the pre-existing Video.js and therefore would be predictably slower. Having speed is advantageous as the site administrators - although quite insignificant on a small scale - 100ms on the next library tested would save some cost at scale. From a user's perspective, being able to watch video on demand as quickly as possible is essential in providing a better user experience.</p>

		<hr>

		<h2>Compatibility</h2>

		<table>
			<thead>
				<tr>
					<td>Library</td>
					<td>Safari</td>
					<td>Firefox</td>
					<td>Chrome</td>
					<td>Opera</td>
					<td>IE9</td>
					<td>IE10+</td>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>Plyr</td>
					<td>Y</td>
					<td>Y</td>
					<td>Y</td>
					<td>Y</td>
					<td>See below</td>
					<td>Y</td>
				</tr>
				<tr>
					<td>Afterglow</td>
					<td>Y</td>
					<td>Y</td>
					<td>Y</td>
					<td>Y</td>
					<td>See below</td>
					<td>Y</td>
				</tr>
				<tr>
					<td>Video.js</td>
					<td>Y</td>
					<td>Y</td>
					<td>Y</td>
					<td>Y</td>
					<td>See below</td>
					<td>Y</td>
				</tr>
			</tbody>
		</table>
		<p>All libraries feature support for modern versions of Safari, Firefox, Chrome, Opera and Internet Explorer(10+). Documentation on this for all three libraries is lacking and it isn't clear as to how they function on older browsers. Plyr notes that the native player is used when in IE9 due to lack of support for HTML5 features. Similarly, Afterglow and Video.js outline IE9 support as limited. Looking to the present however, they are all supported in the latest releases.</p>

		<hr>

		<h2>Documentation</h2>
		<p>Another important criterion of the selection process is documentation. Good documentation provides the information developers need to be able to implement modules into their own work. Both Video.js and Afterglow feature documentation sections on their websites respectively. Plyr opts for documentation through the GitHub repository in Markdown format.</p>
		<p>Plyr's documentation is concise and runs through installation, setup, events and semantics seamlessly. It is notably harder to navigate around the Markdown file that that of the other contenders but nevertheless provided the same stream of information and could be followed.</p>
		<p>Afterglow's documentation is carefully split into useful sections with basics including installation, setup, events and the API intuitively coming first. The documentation flows well, features code snippets and references to other useful sections. In my opinion, Afterglow was easier to implement than the other libraries as the documentation was more cohesive and provided better explanations.</p>
		<p>Video.js' documentation is definitely the more in-depth of the three and provides more guidance on each individual feature of the library such as video tracks for example. I would suggest that Video.js has the better documentation for customisation as a result. For easy implementation and installation however, I would look to one of the other two libraries.</p>
		<p>All being said, Plyr's documentation is lacking behind the format of Afterglow and Video.js and perhaps some of the content as well. Video.js is more in-depth and technically focused with its guides whereas Afterglow is straight to the point and easily navigated. For its documentation, I would suggest Afterglow.</p>

		<hr>

		<h2>Ease of Use</h2>
		<p>Ease of use is arguably one of the most important factors to take into consideration when implementing any sort of user interface that visitors will come in contact with. If the player layout isn't intuitive, is crowded or doesn't function correctly, this is something that should certainly be taken into consideration.</p>

		<link rel="stylesheet" href="node_modules/plyr/dist/plyr.css">
		<div class="video-wrapper">
			<video class="plyr-play player" width="1280" height="720" controls>
			  <source src="resources/media/sample-video.mp4" type="video/mp4">
			</video>
			<span class="caption">
				Plyr Implementation
			</span>
		</div>
		<script src="node_modules/plyr/dist/plyr.js"></script>
		<script>plyr.setup('.plyr-play',[]);</script>
		<p>To start with, Plyr's user interface is very reminiscent of what you'd come to expect with a video player. All of the necessary functions are there for a user. They can mix through the video/audio clip and see the duration of the video. Likewise, there are options for volume and full screen. These controls disappear when the video isn't hovered by default. The default styling is modern and could quite easily sit on most pages without the need for any additional tweaks. Plyr's responsive layout is also notably nicer than Video.js.</p>

		<div class="video-wrapper">
			<video id="vjs" class="video-js player" width="1280" height="720" data-setup='{"fluid": true}' controls>
			  <source src="resources/media/sample-video.mp4" type="video/mp4">
			</video>
			<span class="caption">
				Video.js Implementation
			</span>
		</div>
		<script src="node_modules/video.js/dist/video.js"></script>
		<script>
			var myPlayer = videojs('vjs');
		</script>
		<p>By default, Video.js' player feels quite different to that of Afterglow and Plyr. It seems dated and the controls are small, perhaps harder to see for some users. Their documentation does state that skinning the player is preferred and they do include a CodePen fork to design your own player. Afterglow makes use of the Video.js framework and it seems like Afterglow has tried to refresh Video.js from its fairly uninspiring and lacklustre user interface. Expectedly, the controls are similar to Afterglow for their snappiness.</p>

		<div class="video-wrapper">
			<video class="afterglow player" width="1280" height="720" controls>
			  <source src="resources/media/sample-video.mp4" type="video/mp4">
			</video>
			<span class="caption">
				Afterglow Implementation
			</span>
		</div>
		<script src="node_modules/afterglowplayer/dist/afterglow.min.js"></script>

		<p>In comparison, Afterglow's player takes on a slightly different layout. The play/pause button features at the bottom of the video pane. Similarly, the controls fade on hover out and the user can enable full screen through a button placed in the top right corner. Again, the design is modal and would fit seamlessly with the majority of websites without any customisation. The controls don't feel as snappy as that of Plyr and the icons used don't seem as crisp as those of Plyr. It feels slightly lacking in comparison.</p>

		<p>My recommendation from user experience alone would be Plyr. Its lightweight design transfers across to playback - it feels nicer, quicker and it is well designed by default. Its fresher and the assets are clearer and more defined.</p>


		<hr>

		<h2>Conclusion</h2>
		<p>On final evaluation, my recommendation for implementing video into the global feed would be Plyr. It is lightweight, well-designed and supports YouTube, Vimeo and streaming format out of the box whilst maintaining load speeds. Its documentation is fairly well written and although it lacks some of the depth that the other documentations have, it still provides a good introduction and some steps on customisations through events in JavaScript or design/skinning in CSS. As with the other libraries, it supports all modern browsers and has some coverage for dated versions. </p>
	</div>

_END;
}

// finish off the HTML for this page:
require_once "footer.php";
?>
