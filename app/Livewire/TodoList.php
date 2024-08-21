<?php

namespace App\Livewire;

use App\Models\Todo;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class TodoList extends Component
{
    use WithPagination;
    #[Rule("required|min:3|max:50")]
    public $name = "";
    public $search = "";


    public $editID = "";
    #[Rule("required|min:3|max:50")]
    public $editName = "";

    public function update(){
        $this->validateOnly("editName");
        Todo::where("id",$this->editID)->update([
            "name"=>$this->editName
        ]);
        $this->cancel();
    }
    public function cancel(){
        $this->reset("editID","editName");
    }
    function edit(Todo $todo) {
        $this->editID = $todo->id;
        $this->editName = $todo->name;
    }
    function delete(Todo $todo)
    {
        $todo->delete();
    }

    public function toggle(Todo $todo)
    {

        $todo->update([
            "is_completed" => !$todo->is_completed
        ]);
    }
    public function create()
    {
        $this->validateOnly("name");

        Todo::create([
            "name" => $this->name
        ]);
        $this->reset("name");
        session()->flash("success", "Created");
    }
    public function render()
    {
        return view('livewire.todo-list', [
            "todos" => Todo::latest()->where("name", "like", "%{$this->search}%")->paginate(5)
        ]);
    }
}
