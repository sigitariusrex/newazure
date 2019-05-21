<?php
if (isset($_POST['submit'])) {
	if (isset($_POST['url'])) {
		$url = $_POST['url'];
	} else {
		header("Location: index.php");
	}
} else {
	header("Location: index.php");
}
?>

<!DOCTYPE html>
<html>
	<head>
	</head>
	
	<body>
				
				<script type="text/javascript">
					$(document).ready(function () {
						var subscriptionKey = "fcd18a7c3e884082ac16d3092dcc698f";
						var uriBase = "https://southeastasia.api.cognitive.microsoft.com/vision/v2.0/analyze";
						
						// Request parameters.
						var params = {
							"visualFeatures": "Categories,Description,Color",
							"details": "",
							"language": "en",
						};
						
						// Display the image.
						var sourceImageUrl = "<?php echo $url ?>";
						document.querySelector("#sourceImage").src = sourceImageUrl;
						
						// Make the REST API call.
						$.ajax({
							url: uriBase + "?" + $.param(params),
							
							// Request headers.
							beforeSend: function(xhrObj){
								xhrObj.setRequestHeader("Content-Type","application/json");
								xhrObj.setRequestHeader("Ocp-Apim-Subscription-Key", subscriptionKey);
							},
							type: "POST",
							
							// Request body.
							data: '{"url": ' + '"' + sourceImageUrl + '"}',
						})
							.done(function(data) {
							
							// Show formatted JSON on webpage.
							$("#responseTextArea").val(JSON.stringify(data, null, 2));
							$("#description").text(data.description.captions[0].text);
						})
							.fail(function(jqXHR, textStatus, errorThrown) {
							
							// Display error message.
							var errorString = (errorThrown === "") ? "Error. " :
							errorThrown + " (" + jqXHR.status + "): ";
							errorString += (jqXHR.responseText === "") ? "" :
							jQuery.parseJSON(jqXHR.responseText).message;
							alert(errorString);
						});
					});
				</script>
				<br>
				
				<div id="wrapper" style="width:1020px; display:table;">
    			<div id="jsonOutput" style="width:600px; display:table-cell;">
        Response:
        <br><br>
        <textarea id="responseTextArea" class="UIInput"
                  style="width:580px; height:400px;"></textarea>
    </div>
    <div id="imageDiv" style="width:420px; display:table-cell;">
        Source image:
        <br><br>
        <img id="sourceImage" width="400" />
    </div>
</div>
	</body>
</html>
