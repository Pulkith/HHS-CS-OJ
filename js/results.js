
const bg_colors = ["bg-orange-light", "bg-green", "bg-red"]; //verdict-bg
const button_colors = ["btn--orange", "btn--black", "btn--black"] //verdict-view-code-btn
const line_colors = ["bg-orange-light", "bg-green", "bg-red"] //verdict-colored-line

var status = false;

var user = "";
var problem = ""

const generalTimeLimit = 1000;

const setUIVerdict = function(verdict) {
	//1-> await, 2-> AC, 3-> WA/TLE/MLE/CE/DOJ
	clear_verdict_ui();
	verdict -= 1;
	$("#verdict-bg").addClass(bg_colors[verdict])
	$("#verdict-view-code-btn").addClass(button_colors[verdict]);
	$("#verdict-colored-line").addClass(line_colors[verdict])

	return 1;
}

const clear_verdict_ui = function() {
	$("#verdict-bg").removeClass(["bg-orange-light", "bg-green", "bg-red"])
	$("#verdict-view-code-btn").removeClass(["btn--orange", "btn--black"]);
	$("#verdict-colored-line").removeClass(["bg-orange-light", "bg-green", "bg-red"])
}

const setVerdict = function(submission) {
	setUIVerdict(submission.signal);
	$("#verdict").text(submission.verdict);
	$("#child_verdict").text("Verdict: " + submission.verdict);
	$("#status").text(submission.status);
	$("#time").text("Time: " + submission.time + " ms");
	$("#memory").text("Memory: " + submission.memory + " mb")
	var user_txt = "User: " + submission.user
	var problem_txt = "Problem: " + submission.problem
	$("#user").text(user_txt)
	$("#problem").text(problem_txt)
	$("#tcpass").text("Test Cases Passed: " + submission.tc_pass + " / " + submission.tc_total)
}




var submission = function(_signal, _status, _verdict, _time, _memory, _user, _tc_pass, _tc_total, _problem) {
	this.signal = _signal //1-> await, 2-> AC, 3-> WA/TLE/MLE/CE/DOJ
	this.status = _status;
	this.verdict = _verdict;
	this.time = _time;
	this.memory = _memory;
	this.user = _user;
	this.problem = _problem;
	this.tc_pass = _tc_pass
	this.tc_total = _tc_total
}

const fetch_status = function() {
	if(status == true) return;
	$.ajax({ url: 'OnlineJudge/get_status.php',
		data: {
			"user": user,
			"problem": problem
		},
		type: 'post',
		async: true,
		success: function(output) {
			const data = JSON.parse(output);
			if(data.response == "Judgement Generated") {
				var cur = status;
				status = true;
				if(status == false) {
					//fetch_submission_details(); //In Case Client Side TLE Ran
				}
				fetch_submission_details();
			} else {
				fetch_status();
			}
		}
	});
}

const fetch_submission_details = function() {
	$.ajax({ url: 'OnlineJudge/fetch_submission_results.php',
		data: {
			"user": user,
			"problem": problem
		},
		type: 'post',
		async: true,
		success: function(output) {
			const data = JSON.parse(output);
			if(data.response == "0") {
				const dtls = JSON.parse(data.details);
				var verdict = 3;
				if(dtls.verdict == "Accepted") verdict = 2;
				var sub = new submission(verdict, "Judgement Generated", dtls.verdict,dtls.time, dtls.memory, dtls.user, dtls.tcpass, dtls.tctotal, dtls.problem);
				setVerdict(sub);
			} else {
				var sub = new submission(2, "Judgement Generated", "Judgement Failed", "0", "64", user, 0, "N/A", problem)
				setVerdict(sub);
			}
		}
	});
}


$(document).ready(function() {
	let url = new URL(window.location.href)
	let params = new URLSearchParams(url.search)

	let has_user = params.has('user');
	let has_problem = params.has('problem');

	if(!has_user || !has_problem) window.history.go(-1); //write error
	
	user = params.get('user');
	problem = params.get('problem');
	setTimeout(function(){
		if(status == false) {
			status = true;
			var sub = new submission(2, "Judgement Generated", "Time Limit Exceeded", "2000", "64", user, 0, "N/A", problem)
			setVerdict(sub);
		}
	}, 2000);
	
	fetch_status();
});
  