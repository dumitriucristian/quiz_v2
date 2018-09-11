<div >
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link" href="/admin/quizzes">All Quizzes</a>
        </li>
@if(isset($question))
        <li class="nav-item">
            <a class="nav-link" href="/admin/{{$question->id}}/editQuiz/">Quiz main page</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/admin/{{$question->id}}/editQuiz/">Quiz questions</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/{{$question->id}}/quiz/">Quiz Preview</a>
        </li>
@endif
    </ul>

</div>
