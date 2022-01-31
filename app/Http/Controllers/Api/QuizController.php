<?php

namespace App\Http\Controllers\Api;

use App\Models\Quiz;
use Illuminate\Http\Request;
use App\Http\Requests\QuizRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\QuizResource;
use App\Http\Resources\QuizCollection;

class QuizController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $quizzes = Quiz::filter($request)->latest('id')->paginate(10)->withQueryString();
        return new QuizCollection($quizzes);    
    }
    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(QuizRequest $request)
    {
        $created =  Quiz::create($request->validated());

        if($created){
            return $this->successResponse('Quiz successfully created');
        }else{
            return $this->errorResponse('Quiz could not be created');
        }
    }

    public function show(Quiz $quiz)
    {
        return new QuizResource($quiz);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Quiz  $quiz
     * @return \Illuminate\Http\Response
     */
    public function update(QuizRequest $request, Quiz $quiz)
    {
        $updated =  $quiz->update($request->validated());

        if($updated){
            return $this->successResponse('Quiz successfully updated');
        }else{
            return $this->errorResponse('Quiz could not be updated');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Quiz  $quiz
     * @return \Illuminate\Http\Response
     */
    public function destroy(Quiz $quiz)
    {
        $deleted =  $quiz->delete();

        if($deleted){
            return $this->successResponse('Quiz successfully deleted');
        }else{
            return $this->errorResponse('Quiz could not be deleted');
        }
    }
}
