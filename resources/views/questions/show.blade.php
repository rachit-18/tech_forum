@extends('layouts.app')

<head>
    @vite('resources/css/showQuestions.css')
    <link
        href="https://fonts.googleapis.com/css2?family=Lobster&family=Montserrat:wght@700&family=Open+Sans:wght@600&family=Poppins:wght@400;600&family=Raleway:wght@600&display=swap"
        rel="stylesheet">
</head>

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ $question->Title }}
    </h2>
@endsection

@section('content')
    <main class="container">
        <div class="question-box">
            <div class="question-content">
                <h3>{{ $question->Title }}</h3>
                <p>{{ $question->Content }}</p>
                <p>Asked by: <a class="user-link"
                        href="{{ route('profile.show', ['id' => $question->user_id]) }}">{{ $question->UserName }}</a></p>
                <p>Upvotes: {{ $question->Upvotes }}</p>
                <p>Answered: {{ $question->Answered ? 'Yes' : 'No' }}</p>
                @if ($question->Tags)
                    @php
                        $tags = json_decode($question->Tags, true);
                    @endphp
                    <p>Tags: {{ implode(', ', $tags) }}</p>
                @endif
                <div class="  button-container">
                    <form action="{{ route('questions.upvote', $question->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="btn upvote-btn"></button>
                    </form>

                    <form action="{{ route('questions.downvote', $question->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="btn downvote-btn"></button>
                    </form>

                    <form action="{{ route('report.question', $question->id) }}" method="POST" class="inline">
                        <button type="submit" class="btn report-btn"></button>
                    </form>

                    @if (Auth::user()->role === 'admin')
                        <form action="{{ route('questions.destroy', $question->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn delete-btn"></button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <div class="question-box">
            <div class="question-content">
                <h3>Replies:</h3>
                @forelse($question->replies as $reply)
                    <div class="border-t border-gray-200 mt-4">
                        <p>Asked by: <a class="user-link"
                                href="{{ route('profile.show', ['id' => $reply->user_id]) }}">{{ $reply->UserName }}</a>
                        </p>
                        <p>Upvotes: {{ $reply->Upvotes }}</p>
                        <p>Answered: {{ $question->Answered ? 'Yes' : 'No' }}</p>
                        <p>Created at: {{ $reply->created_at->diffForHumans() }}</p>
                        <div class="  button-container">
                            <form action="{{ route('replies.upvote', $reply->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="btn upvote-btn"></button>
                            </form>

                            <form action="{{ route('replies.downvote', $reply->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="btn downvote-btn"></button>
                            </form>

                            <form action="{{ route('report.reply', $reply->id) }}" method="POST" class="inline">
                                <button type="submit" class="btn report-btn"></button>
                            </form>

                            @if (Auth::user()->role === 'admin')
                                <form action="{{ route('replies.destroy', $reply->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn delete-btn"></button>
                                </form>
                                @endif
                            </div>
                            <pre>{{ $reply->Content }}</pre>
                        </div>
                    @empty
                        <p>No replies yet.</p>
                @endforelse

            </div>
        </div>

        <br>
        {{-- replies form --}}
        <div class="reply-form-box">
            <div class="reply-form">
                <h4>Reply Here:</h4>
                <form action="{{ route('replies.store', $question->id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="Content">Reply:</label>
                        <textarea id="Content" name="Content" class="input-text" rows="4" required></textarea>
                    </div>
                    <button type="submit" class="btn submit-btn">Submit Reply</button>
                </form>
            </div>
        </div>
    </main>
@endsection
