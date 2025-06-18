<?php

namespace App\Controller;

use App\Entity\Country;
use App\Form\CountryType;
use App\Repository\CountryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/country")
 */
class CountryController extends AbstractController
{
    /**
     * @Route("/", name="app_country_index", methods={"GET"})
     */
    public function index(Request $request,CountryRepository $countryRepository): Response
    {
        $searchTerm = $request->query->get('search');
        if($searchTerm){
            $countriesName = $countryRepository->createQueryBuilder('q')
            ->where('q.name LIKE :search')
            ->setParameter('search', '%'.$searchTerm.'%')
            ->getQuery()
            ->getResult();
        }else{
            $countriesName = $countryRepository->findAll();
        }
        return $this->render('country/index.html.twig', [
            'countries' => $countriesName
        ]);
    }

    /**
     * @Route("/new", name="app_country_new", methods={"GET", "POST"})
     */
    public function addnewCountry(Request $request, CountryRepository $countryRepository): Response
    {
        $country = new Country();
        $form = $this->createForm(CountryType::class, $country);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $countryRepository->add($country, true);

            return $this->redirectToRoute('app_country_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('country/new.html.twig', [
            'country' => $country,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_country_show", methods={"GET"})
     */
    public function showCountry(Country $country): Response
    {
        return $this->render('country/show.html.twig', [
            'country' => $country,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_country_edit", methods={"GET", "POST"})
     */
    public function editCountry(Request $request, Country $country, CountryRepository $countryRepository): Response
    {
        $form = $this->createForm(CountryType::class, $country);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $countryRepository->add($country, true);

            return $this->redirectToRoute('app_country_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('country/edit.html.twig', [
            'country' => $country,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_country_delete", methods={"POST"})
     */
    public function deleteCountry(Request $request, Country $country, CountryRepository $countryRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$country->getId(), $request->request->get('_token'))) {
            $countryRepository->remove($country, true);
        }

        return $this->redirectToRoute('app_country_index', [], Response::HTTP_SEE_OTHER);
    }
}
