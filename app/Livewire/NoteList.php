<?php

namespace App\Livewire;

use App\Models\Note;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;
use Symfony\Contracts\Service\Attribute\Required;

class NoteList extends Component
{
    use WithPagination;
    #[Validate('required', message :'Please provide the content of the note .')]
    public $body;
    public $updating = false;
    public $noteToUpdate;

   
    public function saveNote(){
        $validated= $this->validate();
      Note::create($validated);
      $this->clearData();
    }

    public function editNote(Note $note){
        $this->resetValidation();
        $this->updating= true;
        $this->noteToUpdate = $note;
        $this->body = $note->body;
    }

    public function updateNote(){ 
         $validated= $this->validate();   
         $this->noteToUpdate->update($validated);
        $this->clearData();
    }
    public function noteDone(){    
        $this->noteToUpdate->update([
            'done' => 1
        ]);
        $$this->clearData();
    }
   

    public function deleteNote(Note $note){
        $note->delete();
        $this->clearData();
    }

    public function clearData(){
        $this->body = '';
        if($this->updating) {
            $this->updating = false;
            $this->noteToUpdate = '';
        } 
        $this->resetValidation();
    }


    public function render()
    {
        return view('livewire.note-list', [
            'notes'=>Note::latest()->simplePaginate(3)
        ]);
    }
}
