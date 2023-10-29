<?php

namespace App\Services;

use App\Models\Support;
use App\Models\SupportComment;
use App\Traits\UploadAble;

class SupportService 
{

  use UploadAble;

  protected $support;
  protected $comment;

  public function __construct(Support $support, SupportComment $comment) {
    $this->support = $support;
    $this->comment = $comment;
  }

  public function index() {
    return $this->support->orderBy('created_at', 'DESC')->paginate(100);
  }

  public function store($request) {
    return $this->support->create([
      'user_id' => $request->user_id,
      'title' => $this->title,
      'message' => $this->message,
      'status' => 'pending',    
      'active_status' => true,  
      'attachment' => $request->file('attachment') ? $this->uploadOne($request->attachment, 'support') : NULL,
    ]);
  }

  public function createComment($request) {
    return $this->comment->create([
      'ticket_id' => $request->user_id,
      'message' => $this->message,
      'attachment' => $request->file('attachment') ? $this->uploadOne($request->attachment, 'support') : NULL,
    ]);
  }

  public function findTicket($id) {
    return $this->support->find($id);
  }

  public function updateTicket($request, $id) {
    $ticket = $this->support->find($id);

    if($ticket) {
      return $ticket->update($request->all());
    }

    return false;
  }

  public function deleteTicket($id) {
    $ticket = $this->support->find($id);

    if($ticket) {
      return $ticket->delete();
    }

    return false;    
  }
  
}
