<div class="card comment mt-4" id="comment{{ $comment->id }}">
    <div class="card-body">
        <div class="row">
            <img src="@if($comment->user) {{ $comment->user->avatar() }} @else {{ $comment->defaultAvatar() }} @endif" class="col-3 align-self-start img-fluid" style="object-fit: contain;">
            
            <div class="col-9">
                <div>
                    <h4 class="d-inline comment-title">
                        @if(empty($comment->deleted_at))
                        {{ $comment->title }}
                        @else
                        Comment deleted
                        @endif
                    </h4>
                    <span class="text-small text-muted font-italic comment-caption">
                        {{ $comment->created_at->format('j F, Y') }} by @if($comment->user) {{ $comment->user->login }} @else deleted user @endif
                    </span>
                   
                    @if((Auth::user() && (Auth::user()->is('admin') || (!empty($comment->user->id) && Auth::user()->id == $comment->user->id))) && empty($comment->deleted_at))
                        <div class="d-flex justify-content-end pull-right">
                            <a href="{{ route('comments.edit', $comment->id) }}" class="link-comment-edit d-iniline text-primary">
                                <i class="fas fa-pencil-alt"></i>
                            </a>

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
                    @endif
                </div>

                <div class="comment-body my-2">
                    @if(empty($comment->deleted_at))
                    {!! $comment->body !!}
                    @else
                    Comment deleted
                    @endif
                </div>
            </div>

            <div class="col-9 offset-3">
                <div class="w-100 d-flex justify-content-end align-items-end">
                    @if(empty($comment->deleted_at))
                    <div class="resource-actions align-items-center mr-2 mr-lg-3">

                        {{ Form::open([
                            'route' => ['ratings.rate.comment', $comment->id],
                            'method' => 'POST',
                            'class' => 'd-inline-block form-inline-block mr-2 mr-lg-3 form-comment-like ' . 
                                (($comment->likedBy(Auth::id())) ? 'text-primary' : 'text-muted'),
                            'title' => 'Like'
                        ]) }}

                            {{ Form::hidden('rating_type', 'like') }}

                            <div>
                                <label class="mb-0">
                                    <i class="fas fa-thumbs-up" style="cursor: pointer;"></i>
                                    <button type="submit" class="d-none"></button>
                                </label>
                                <span class="comment-likes-count">{{ $comment->likes()->count() }}</span>
                            </div>

                        {{ Form::close() }}


                        {{ Form::open([
                            'route' => ['ratings.rate.comment', $comment->id],
                            'method' => 'POST',
                            'class' => 'd-inline-block form-inline-block mr-2 mr-lg-3 form-comment-dislike ' . 
                                (($comment->dislikedBy(Auth::id())) ? 'text-primary' : 'text-muted'),
                            'title' => 'Dislike'
                        ]) }}

                            {{ Form::hidden('rating_type', 'dislike') }}

                            <div>
                                <label class="mb-0">
                                    <i class="fas fa-thumbs-down" style="cursor: pointer;"></i>
                                    <button type="submit" class="d-none"></button>
                                </label>
                                <span class="comment-dislikes-count">{{ $comment->dislikes()->count() }}</span>
                            </div>

                        {{ Form::close() }}

                    </div>
                    @endif

                    @if($depth > 1 && empty($comment->deleted_at))
                    <a href="{{ route('comments.create', $comment->id) }}" class="btn btn-sm btn-outline-primary pull-right btn-show-comment-create">
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

                        <label for="body">Body</label>
                        <textarea class="form-control mb-3" rows="5" style="resize: none;" required="" maxlength="2000" pattern="[\w\-\+\#\$\%\^\&\@\!\(\)\|\=\,\.\;\:\<\>\/\?\"\' ]+" data-validator="true" data-validator-required="true" data-validator-maxlength="2000" data-validator-pattern="^[\w\-\+\#\$\%\^\&\@\!\;\:\(\)\|\=\,\.\<\>\/\?\'\" ]+$" data-validator-required-message="Comment body is required." data-validator-maxlength-message="Comment body must be equal or less than 2000 characters long." data-validator-pattern-message="Comment body contains forbidden characters." name="body" cols="50" id="body"></textarea>

                        <input class="btn btn-primary btn-block mt-3" type="submit" value="Create">
                    </form>
                </div>
                @endif
            </div>

            <div class="col-12">
                <div class="comment-subcomments">
                    @if(count($comment->subcomments))
                        @foreach($comment->subcomments->sortByDesc('id') as $subcomment)
                            @include('includes.components.comment', ['comment' => $subcomment, 'depth' => $depth - 1])
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>