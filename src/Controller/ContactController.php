<?php

namespace App\Controller;

use App\Routing\Attribute\Route;

class ContactController
{
  #[Route("/contact/{id}", name: "contact_page")]
  public function contact($id)
  {
    echo "Page de contact avec l'id = $id";
  }

  #[Route("/devis", name: "page_devis")]
  public function devis()
  {
    echo "Page de devis";
  }
}
