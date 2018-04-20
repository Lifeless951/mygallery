document.addEventListener('DOMContentLoaded', function() {
	var inputFile = document.getElementById('fileNameSource');
	var fileNameReceiver = document.getElementById('fileNameReceiver');

	inputFile.addEventListener('change', function(event){
		var target = event.target;
		var fileName = target.value.match(/[^\\]+?$/);
		fileNameReceiver.value = fileName;
	});
});