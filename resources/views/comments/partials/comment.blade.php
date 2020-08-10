<div class="card my-4 comment" id="comment{{ $comment->id }}">
    <div class="card-body">
        <div class="row">
            <div class="col-3"></div>

            <div class="col-9 offset-3">
                <div>
                    <h4 class="d-inline comment-title">
                        {{ $comment->title }}
                    </h4>
                    <span class="text-small text-muted font-italic">
                        {{ $comment->created_at->format('j F, Y') }} by {{ $comment->user->name }} {{ $comment->user->surname }}
                    </span>
                    <div class="d-flex justify-content-end pull-right">
                        {{ Form::open([
                            'route' => ['comments.update', $comment->id],
                            'method' => 'PATCH',
                            'class' => 'd-inline float-right mr-2 form-comment-edit',
                            'title' => 'Edit comment'
                        ]) }}

                            <label>
                                <i class="fas fa-pencil-alt text-primary" style="cursor: pointer;"></i>
                                <button type="submit" class="d-none"></button>
                            </label>

                        {{ Form::close() }}

                        {{ Form::open([
                            'route' => ['comments.destroy', $comment->id],
                            'method' => 'DELETE',
                            'class' => 'd-inline float-right ml-2 form-comment-delete',
                            'title' => 'Delete comment'
                        ]) }}

                            <label>
                                <i class="fas fa-trash-alt text-danger" style="cursor: pointer;"></i>
                                <button type="submit" class="d-none"></button>
                            </label>

                        {{ Form::close() }}
                    </div>
                </div>

                <div class="comment-body my-2">
                    {!! $comment->body !!}
                </div>
            </div>

            <div class="col-12">
                <div class="w-100 d-flex justify-content-end align-items-end">
                    <div class="comment-actions align-items-center mr-2 mr-lg-3">

                        {{ Form::open([
                            'route' => ['ratings.rate.comment', $comment->id],
                            'method' => 'Post',
                            'class' => 'd-inline-block mr-2 mr-lg-3 form-comment-rating',
                            'title' => 'Like'
                        ]) }}

                            {{ Form::hidden('rating_type', 'like') }}

                            <div class="comment-rating comment-likes @if($comment->likedBy(Auth::id())) text-primary @else text-muted @endif">
                                <label class="mb-0">
                                    <i class="fas fa-thumbs-up" style="cursor: pointer;"></i>
                                    <button type="submit" class="d-none"></button>
                                </label>
                                <span class="comment-likes-count">{{ $comment->likes()->count() }}</span>
                            </div>

                        {{ Form::close() }}

                        

                        {{ Form::open([
                            'route' => ['ratings.rate.comment', $comment->id],
                            'method' => 'Post',
                            'class' => 'd-inline-block mr-2 mr-lg-3 form-comment-rating',
                            'title' => 'Dislike'
                        ]) }}

                            {{ Form::hidden('rating_type', 'dislike') }}

                            <div class="comment-rating comment-dislikes @if($comment->dislikedBy(Auth::id())) text-primary @else text-muted @endif">
                                <label class="mb-0">
                                    <i class="fas fa-thumbs-down" style="cursor: pointer;"></i>
                                    <button type="submit" class="d-none"></button>
                                </label>
                                <span class="comment-dislikes-count">{{ $comment->dislikes()->count() }}</span>
                            </div>

                        {{ Form::close() }}

                    </div>

                    @if($depth > 1)
                    <a href="{{ route('comments.create', $comment->id) }}" class="btn btn-sm btn-outline-primary pull-right btn-subcomments-show">
                        Comment
                    </a>
                    @endif
                </div>

                @if($depth > 1)
                <div class="my-1">
                    <form action="{{ route('comments.store') }}" method="POST" accept-charset="UTF-8" class="form-subcomment-create d-none">
                        <input name="_token" type="hidden" value="{{ csrf_token() }}">

                        <input name="article" type="hidden" value="{{ $comment->article->id }}">

                        @if($comment->parent())
                        <input name="parent_comment_id" type="hidden" value="{{ $comment->id }}">
                        @endif

                        <label for="title">Title</label>
                        <input class="form-control mb-3" required="" minlength="5" maxlength="255" pattern="[\w\-\+\#\$\%\^\&\@\!\(\)\|\;\:\=\,\.\<\>\/\?\'\" ]+" data-validator="true" data-validator-required="true" data-validator-minlength="255" data-validator-pattern="^[\w\-\+\#\$\%\^\&\@\;\:\!\(\)\|\=\,\.\<;\>;\/\?\"\' ]+$" data-validator-required-message="Comment title is required." data-validator-minlength-message="Comment title must be equal or longer than 5 characters long." data-validator-maxlength-message="Comment title must be equal or less than 255 characters long." data-validator-pattern-message="Comment title contains forbidden characters." name="title" type="text" value="" id="title">

                        <label for="nick">Nick</label>
                        <input class="form-control mb-3" required="" minlength="5" maxlength="255" pattern="[\w\-\+\#\$\%\^\;\:\&\@\!\(\)\|\=\,\.\<\>\/\?\' ]+" data-validator="true" data-validator-required="true" data-validator-minlength="255" data-validator-pattern="^[\w\-\+\#\$\%\^\&\@\!\(\)\|\=\;\:\,\.\<\>\/\?\"\' ]+$" data-validator-required-message="Comment nick is required." data-validator-minlength-message="Comment nick must be equal or longer than 5 characters long." data-validator-maxlength-message="Comment nick must be equal or less than 255 characters long." data-validator-pattern-message="Comment nick contains forbidden characters." name="nick" type="text" value="" id="nick">

                        <label for="body">Body</label>
                        <textarea class="form-control mb-3" rows="5" style="resize: none;" required="" maxlength="800" pattern="[\w\-\+\#\$\%\^\&\@\!\(\)\|\=\,\.\;\:\<\>\/\?\"\' ]+" data-validator="true" data-validator-required="true" data-validator-maxlength="800" data-validator-pattern="^[\w\-\+\#\$\%\^\&\@\!\;\:\(\)\|\=\,\.\<\>\/\?\'\" ]+$" data-validator-required-message="Comment body is required." data-validator-maxlength-message="Comment body must be equal or less than 800 characters long." data-validator-pattern-message="Comment body contains forbidden characters." name="body" cols="50" id="body"></textarea>

                        <input class="btn btn-primary btn-block mt-3" type="submit" value="Create">
                    </form>
                </div>
                @endif
                
                <div class="comment-subcomments">
                    @if(count($comment->subcomments))
                        @foreach($comment->subcomments->sortByDesc('id') as $subcomment)
                            @include('includes.partials.comment', ['comment' => $subcomment, 'depth' => $depth - 1])
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>