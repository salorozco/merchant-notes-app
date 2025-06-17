<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNoteRequest;
use App\Http\Resources\NoteResource;
use App\Models\Merchant;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class NoteController extends Controller
{
    /**
     * @param Merchant $merchant
     * @return AnonymousResourceCollection
     */
    public function index(Merchant $merchant): AnonymousResourceCollection
    {
        return NoteResource::collection(
            $merchant->notes()->with('user')->latest()->get()
        );
    }

    /**
     * @param StoreNoteRequest $request
     * @param Merchant $merchant
     * @return NoteResource
     */
    public function store(StoreNoteRequest $request, Merchant $merchant): NoteResource
    {
        $note = $merchant->notes()->create([
            'body' => $request->input('body'),
            'user_id' => $merchant->user_id,
        ]);

        return new NoteResource($note);
    }

    /**
     * @param Request $request
     * @param Merchant $merchant
     * @param Note $note
     * @return NoteResource
     */
    public function update(Request $request, Merchant $merchant, Note $note): NoteResource
    {
        $note->update([
            'body' => $request->body,
        ]);

        return new NoteResource($note);
    }

    /**
     * @param Merchant $merchant
     * @param Note $note
     * @return Response
     */
    public function destroy(Merchant $merchant, Note $note)
    {
        $note->delete();

        return response()->noContent();
    }
}
