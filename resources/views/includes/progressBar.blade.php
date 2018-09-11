<<div class="row">
    <div class="col-md-8 col-sm-12 text-sm-left text-center">

        {{$quizInfo['nrOfAnswers']}} answers out of {{$quizInfo['totalNrOfQuestions']}} questions                                </div>
    <div class="col-md-4 col-sm-12 text-center text-md-right">
        <span class="float-md-right">{{$quizInfo['userProgress']}}%</span>
    </div>
</div>
<div class="progress">
    <div class="progress-bar progress-bar-striped bg-success" role="progressbar"
         style="width: {{$quizInfo['userProgress']}}%" aria-valuenow="{{$quizInfo['userProgress']}}" aria-valuemin="0" aria-valuemax="100">
    </div>
</div>
