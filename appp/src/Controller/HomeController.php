<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{

    #[Route('/', name: 'app_home')]
    public function home(EntityManagerInterface $entityManager)
    {
        $contacts = $entityManager->getRepository(Contact::class)->findByGreaterThan(18);

        return $this->render('home.html.twig', [
            "contacts" => $contacts
        ]);
    }

    #[Route('/contact/{id}', name: 'app_contact')]
    public function contact($id, EntityManagerInterface $entityManager)
    {
        $contact = $entityManager->getRepository(Contact::class)->find($id);
        return $this->render('contact.html.twig', [
            "contact" => $contact
        ]);
    }

    /*
    #[Route('/add', name: 'app_add')]
    public function add(EntityManagerInterface $entityManager)
    {

        $contact = new Contact;
        $contact->setNom("Durand");
        $contact->setPrenom("Théo");
        $contact->setTelephone("0123456789");
        $contact->setAge(12);

        $entityManager->persist($contact);
        $entityManager->flush();

        return new Response("<h1>Contact ajouté</h1>");
    }
    */

    #[Route('admin/add', name: 'app_add')]
    public function addContact(Request $request, EntityManagerInterface $entityManager)
    {
        $new_contact = new Contact;
        $form = $this->createForm(ContactType::class, $new_contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($new_contact);
            $entityManager->flush();

            $this->addFlash("contact_add_success", "Contact ajouté avec succès");
            return $this->redirectToRoute('app_home');
        }

        return $this->render('ajouter.html.twig', [
            "form" => $form
        ]);
    }


    /*
    #[Route('/contact/edit/{id}', name: 'app_edit_contact')]
    public function edit($id, EntityManagerInterface $entityManager)
    {
        $contact = $entityManager->getRepository(Contact::class)->find($id);

        $contact->setTelephone('New number !');
        $entityManager->flush();

        return $this->redirectToRoute('app_home');
    }
    */

    #[Route('admin/contact/edit/{id}', name: 'app_edit_contact')]
    public function edit($id, EntityManagerInterface $entityManager, Request $request)
    {
        $contact = $entityManager->getRepository(Contact::class)->find($id);
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->flush();

            $this->addFlash("contact_edit_success", "Contact modifié avec succès");
            return $this->redirectToRoute('app_home');
        }

        return $this->render('modifier.html.twig', [
            "form" => $form
        ]);
    }


    #[Route('admin/contact/delete/{id}', name: 'app_delete_contact')]
    public function delete($id, EntityManagerInterface $entityManager)
    {
        $contact = $entityManager->getRepository(Contact::class)->find($id);

        $entityManager->remove($contact);
        $entityManager->flush();

        return $this->redirectToRoute('app_home');
    }
}
