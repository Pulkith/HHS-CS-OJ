
const bg_colors = ["bg-orange-light", "bg-green", "bg-red"]; //verdict-bg
const button_colors = ["btn--orange", "btn--black", "btn--black"] //verdict-view-code-btn
const line_colors = ["bg-orange-light", "bg-green", "bg-red"] //verdict-colored-line

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
	$("#user").text("User: " + submission.user + " mb")
	$("#tcpass").text("Test Cases Passed: " + submission.tc_pass + " / " + submission.tc_total)
}




var submission = function(_signal, _status, _verdict, _time, _memory, _size, _tc_pass, _tc_total) {
	this.signal = _signal //1-> await, 2-> AC, 3-> WA/TLE/MLE/CE/DOJ
	this.status = _status;
	this.verdict = _verdict;
	this.time = _time;
	this.memory = _memory;
	this.user = _user;
	this.tc_pass = _tc_pass
	this.tc_total = _tc_total
}