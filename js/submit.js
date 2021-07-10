var solution_source = "";

const submit = function(){

	solution_source = $("#solution_text").val()

	if(solution_source.length > 0) {
		push(solution_source)
	} else {
		console.log("Error Code 4");
	}
} 

const push = function(solution_data) {

	$.ajax({ url: 'OnlineJudge/submit_solution.php',
		data: {
			"solution": solution_data,
			"problem": "000A",
			"user": "Monkey"
		},
		type: 'post',
		async: true,
		success: function(output) {
			if(output == 'request posted') {
				window.location.href="results.html?user=Monkey&problem=000A";
			}
			//if(output == -999)login();
			//if(output == "0") {
				//console.log("Solution pushed successfully");
				//window.location.href="results.html";
			//} else {
				//console.log(output)
				//console.log("Error code: " + output)
			//}
		},
		error: function(output, error) {
			console.log(error);
			console.log(output);
		}
	});

}