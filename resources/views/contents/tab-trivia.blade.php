<div class="tab-pane fade" id="pills-trivia" role="tabpanel" aria-labelledby="pills-trivia-tab">
    <form class="forms-sample">
        @foreach($trivia as $row)
        <div class="form-group">
            <label for="answer_content">{{ $row->trivia_question }}</label>
            <input type="text" class="form-control" id="answer_content" value="{{ $row->answer_content }}" readonly>
        </div>
        @endforeach
    </form>
</div>

@push('script')
<script>
</script>
@endpush