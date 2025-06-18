<?php

namespace App\Controller;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Entity\City;
use App\Form\CityType;
use App\Form\MultipleCityType;
use App\Repository\CityRepository;
use App\Repository\CountryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/city")
 */
class CityController extends AbstractController
{
    /**
     * @Route("/", name="app_city_index", methods={"GET"})
     */
    public function index(Request $request, CityRepository $cityRepository, CountryRepository $countryRepository): Response
    {
        $search = $request->query->get('search');
        $status = $request->query->get('status');
        $countryId = $request->query->get('country');


        $cities = $cityRepository->findWithFilters($search, $status, $countryId);
        $countries = $countryRepository->findAll();
        return $this->render('city/index.html.twig', [
            'cities' => $cities,
            'search' => $search,
            'status' => $status,
            'selectedCountry' => $countryId,
            'countries' => $countries
        ]);
    }


    // /**
    //  * @Route("/new", name="app_city_new", methods={"GET", "POST"})
    //  */
    // public function new(Request $request, CityRepository $cityRepository): Response
    // {
    //     $city = new City();
    //     $form = $this->createForm(CityType::class, $city);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $cityRepository->add($city, true);

    //         return $this->redirectToRoute('app_city_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->renderForm('city/new.html.twig', [
    //         'city' => $city,
    //         'form' => $form,
    //     ]);
    // }

    /**
     * @Route("/{id}", name="app_city_show", methods={"GET"})
     */
    public function show(City $city): Response
    {
        return $this->render('city/show.html.twig', [
            'city' => $city,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_city_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, City $city, CityRepository $cityRepository): Response
    {
        $form = $this->createForm(CityType::class, $city);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cityRepository->add($city, true);

            return $this->redirectToRoute('app_city_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('city/edit.html.twig', [
            'city' => $city,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_city_delete", methods={"POST"})
     */
    public function delete(Request $request, City $city, CityRepository $cityRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $city->getId(), $request->request->get('_token'))) {
            $city->setIsDeleted(true);
            $cityRepository->add($city, true);
            $this->addFlash('success', 'City deleted successfully.');
        }

        return $this->redirectToRoute('app_city_index');
    }
   /**
     * @Route("/city/add", name="app_addcity_new")
     */
    public function addCityPage(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CityType::class);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
           
            $country = $form->get('country')->getData();
            $active = $form->get('active')->getData();
    
            
            $firstCity = trim($form->get('name')->getData());
    
           
            $cityNames = $request->request->all('cities'); 
    
          
            $allCities = [];
            if ($firstCity !== '') {
                $allCities[] = $firstCity;
            }
            foreach ($cityNames as $name) {
                $name = trim($name);
                if ($name !== '') {
                    $allCities[] = $name;
                }
            }
    
            
            if (count($allCities) === 0) {
                $form->addError(new FormError('Please add at least one city.'));
            } else {
             
                foreach ($allCities as $cityName) {
                    $city = new City();
                    $city->setName($cityName);
                    $city->setCountry($country);
                    $city->setActive($active);
                    $city->setIsDeleted(false);  
                    $entityManager->persist($city);
                }
                $entityManager->flush();
    
                $this->addFlash('success', 'Cities added successfully.');
    
                return $this->redirectToRoute('app_city_index');
            }
        }
    
        return $this->render('city/multiple_new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
