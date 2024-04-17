<?php

namespace App\Controller;

use App\Entity\Exercicesastistics;
use App\Entity\Exercises;
use App\Entity\Product;
use App\Form\ExercisesType;
use App\Repository\ExercisesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\Favoriteexercices;

class ExerciseController extends AbstractController
{
    #[Route('/dashboard/exercise', name: 'exercisePage')]
    public function index(): Response
    {
        $exercise = new Exercises();
        $form = $this->createForm(ExercisesType::class, $exercise);
        $entityManager = $this->getDoctrine()->getManager();

        $satisticsdata = $entityManager->getRepository(Exercicesastistics::class)->findAll();
        $exercises = $entityManager->getRepository(Exercises::class)->findAll();
        
        return $this->render('dashboard/exercise/exercises.html.twig', [
            'form' => $form->createView(),
            'exercises' => $exercises,
            'exercise' =>null,
            'modify' => false,
            'satisticsdata' => $satisticsdata,
        ]);
    }
    
    #[Route('/dashboard/exercise/save/{id?}', name: 'save_exercise')]
    public function save(Request $request, Exercises $exercise = null): Response
    {
        if (!$exercise) {
            $modify=false;
            $exercise = new Exercises();
        }
        else{
            $modify=true;
        }
    
        $form = $this->createForm(ExercisesType::class, $exercise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $exercise->setImg($form->get('img')->getData()->getClientOriginalName());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($exercise);
            $entityManager->flush();
            return $this->redirectToRoute('exercisePage');
        }
    
        $entityManager = $this->getDoctrine()->getManager();
        $exercises = $entityManager->getRepository(Exercises::class)->findAll();
        $products = $entityManager->getRepository(Product::class)->findAll();
        $productChoices = [];

        foreach ($products as $product) {
            $productChoices[$product->getName()] = $product->getId();
        }
        


        $satisticsdata = $entityManager->getRepository(Exercicesastistics::class)->findAll();
        return $this->render('dashboard/exercise/exercises.html.twig', [
            'exercise' => $exercise,
            'form' => $form->createView(),
            'exercises' => $exercises,
            'modify' => $modify,
            'productChoices' => $productChoices,
            'satisticsdata' => $satisticsdata,
        ]);
    }
    
    #[Route('/dashboard/exercise/delete/{id}', name: 'delete_exercise')]
    public function delete(Request $request, Exercises $exercise): Response
    {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($exercise);
            $entityManager->flush();
        return $this->redirectToRoute('exercisePage');
    }

    #[Route ('/dashboard/exercise/filtred', name:'filtre_exercises') ]
    public function filtreExercisesAction(Request $request,ExercisesRepository $exercisesRepository)
    {
        // Get the values of the checkboxes from the request
        $target = $request->request->get('target');
        $type = $request->request->get('type');
        
        
        $filteredExercises = $exercisesRepository->findByTargetAndType($target, $type);
        
        
        
        
        $exercise = new Exercises();
        $form = $this->createForm(ExercisesType::class, $exercise);
        $entityManager = $this->getDoctrine()->getManager();
        $satisticsdata = $entityManager->getRepository(Exercicesastistics::class)->findAll();


        return $this->render('dashboard/exercise/exercises.html.twig', [
            'form' => $form->createView(),
            'exercises' => $filteredExercises,
            'exercise' =>null,
            'modify' => false,
            'satisticsdata' => $satisticsdata,
        ]);
        
    }




 // app front   

         //wishlist metier

        #[Route('/app/exercises/favorite', name: 'favorite')]
        public function favoritePage(SessionInterface $session): Response
        {
            $favoriteExercises = [];
            if($session->has('accountuser')){
                $entityManager = $this->getDoctrine()->getManager();
                $exercises = $entityManager->getRepository(Exercises::class)->findAll();
                $user = $session->get('accountuser');
                $favoriteRepository = $entityManager->getRepository(Favoriteexercices::class);
                $favorites = $favoriteRepository->findBy(['iduser' => $user->getId()]); 

                foreach($favorites as $favorite){                  
                    $favoriteExerciseId = $favorite->getIdexercice();
                    // Use array_filter to find the exercise with the matching ID
                    $filteredExercises = array_filter($exercises, function($exercise) use ($favoriteExerciseId) {
                        return $exercise->getId() === $favoriteExerciseId;
                    });
                    $favoriteExercises = array_merge($favoriteExercises, $filteredExercises);

                }           
     
            }

            return $this->render('app_front/exercise/favoriteExercises.html.twig', [
                'favorites' => $favoriteExercises,
            ]);
        }



        #[Route('/app/exercises/check/{id}', name: 'favoritecheck')]
        public function favoritecheck(SessionInterface $session, Exercises $exercise): Response
        {

            if ($session->has('accountuser')) {
                $entityManager = $this->getDoctrine()->getManager();
                $user = $session->get('accountuser');
        
                $favoriteRepository = $entityManager->getRepository(Favoriteexercices::class);
                $favorite = $favoriteRepository->findOneBy([
                    'iduser' => $user->getId(),
                    'idexercice' => $exercise->getId()
                ]);
               

                if ($favorite) {
                    $entityManager->remove($favorite);
                    $entityManager->flush();
                    
                    //score statistic --
                    $statistics = $entityManager->getRepository(Exercicesastistics::class)->findOneBy(['type' => $exercise->getType()]);
                    if ($statistics) {
                        if($statistics->getScore() > 0){
                            $statistics->setScore($statistics->getScore() - 1);
                        }
                        $entityManager->persist($statistics);
                        $entityManager->flush();
                    }
                    
                } else {
                    $favorite = new Favoriteexercices();
                    $favorite->setIduser($user->getId());
                    $favorite->setIdexercice($exercise->getId());
                    $favorite->setType($exercise->getType());
                    $entityManager->persist($favorite);
                    $entityManager->flush();
 
                    //score statistic ++
                    $statistics = $entityManager->getRepository(Exercicesastistics::class)->findOneBy(['type' => $exercise->getType()]);
                    if ($statistics) {
                        $statistics->setScore($statistics->getScore() + 1);
                        $entityManager->persist($statistics);
                        $entityManager->flush();
                    }
                    
                }
        
                return $this->redirectToRoute('favorite');
            } 
            else {
                return $this->redirectToRoute("app_front_signin");
            }
        }
        





    #[Route('/app/exercises/WorkoutPlanform', name: 'workoutPlanFormPage')]
    public function workoutPlanFormPage(): Response
    {
        return $this->render('app_front/exercise/workoutForm.html.twig', [
        ]);
    }



    #[Route('/app/exercises/WorkoutPlan', name: 'workoutplanLoad')]
    public function workoutPlan(Request $request,SessionInterface $session): Response
    {
        // Get form data
        $goal = $request->request->get('goal');
        $type = $request->request->get('type');
        $level = $request->request->get('level');
        $location = $request->request->get('location');
        $problems = $request->request->get('problems');

           // Adjust level if problems are serious
        if ($problems === 'Yes') {
            $level = 'Easy';
        }
        // Generate workout plan based on the type
        $workoutPlan = [];
        if ($type == 'Split') {
            $workoutPlan = $this->generateSplitWorkout($goal, $level, $location);

            if ($session->has('accountuser')) {
                $user = $session->get('accountuser');
                $message = $this->MessageGenerate($workoutPlan);
                $this->sendSms($user->getPhonenumber(),"Fitlife",$message);
            }

            return $this->render('app_front/exercise/workoutFormsplit.html.twig', [
            'workoutplan' =>$workoutPlan,
            ]);
        } elseif ($type == 'Push Pull Leg') {
            $workoutPlan = $this->generatePushPullLegWorkout($goal, $level, $location);

            if ($session->has('accountuser')) {
                $user = $session->get('accountuser');
                $message = $this->MessageGenerate($workoutPlan);
                $this->sendSms($user->getPhonenumber(),"Fitlife",$message);
            }

            return $this->render('app_front/exercise/workoutFormppl.html.twig', [
                'workoutplan' =>$workoutPlan,
                ]);
        }

        

        return $this->redirectToRoute('workoutPlanFormPage');
    }    

    private function generateSplitWorkout($goal, $level, $location)
    {
        // Retrieve exercises for each muscle group based on the user's input
        $chestExercises = $this->getDoctrine()->getRepository(Exercises::class)->findBy([
            'type' => $goal,
            'intensity' => $level,
            'equipmentneeded' => $location,
            'target' => ['Chest','Abs'], // Adjust this according to your database schema
        ]);
    
        $backExercises = $this->getDoctrine()->getRepository(Exercises::class)->findBy([
            'type' => $goal,
            'intensity' => $level,
            'equipmentneeded' => $location,
            'target' => ['Back','Abs'], // Adjust this according to your database schema
        ]);
    
        $shoulderExercises = $this->getDoctrine()->getRepository(Exercises::class)->findBy([
            'type' => $goal,
            'intensity' => $level,
            'equipmentneeded' => $location,
            'target' => ['Shoulder','Abs'], // Adjust this according to your database schema
        ]);
    
        $legExercises = $this->getDoctrine()->getRepository(Exercises::class)->findBy([
            'type' => $goal,
            'intensity' => $level,
            'equipmentneeded' => $location,
            'target' => ['Leg','Abs'], // Adjust this according to your database schema
        ]);
    
        $absExercises = $this->getDoctrine()->getRepository(Exercises::class)->findBy([
            'type' => $goal,
            'intensity' => $level,
            'equipmentneeded' => $location,
            'target' => 'Abs', // Adjust this according to your database schema
        ]);
    

        // You can return these as an array or any other format you prefer
        $workoutPlan = [
            'ChestDay' => $chestExercises,
            'BackDay' => $backExercises,
            'ShoulderDay' => $shoulderExercises,
            'LegDay' => $legExercises,
            'AbsDay' => $absExercises,
        ];

    
        return $workoutPlan;
    }
    


    private function generatePushPullLegWorkout($goal, $level, $location)
    {
        // Retrieve exercises for each muscle group based on the user's input
        $chestShoulderExercises = $this->getDoctrine()->getRepository(Exercises::class)->findBy([
            'type' => $goal,
            'intensity' => $level,
            'equipmentneeded' => $location,
            'target' => ['Chest', 'Shoulder'], // Adjust this according to your database schema
        ]);
    
        $backArmExercises = $this->getDoctrine()->getRepository(Exercises::class)->findBy([
            'type' => $goal,
            'intensity' => $level,
            'equipmentneeded' => $location,
            'target' => ['Back', 'Arm'], // Adjust this according to your database schema
        ]);
    
        $legAbsExercises = $this->getDoctrine()->getRepository(Exercises::class)->findBy([
            'type' => $goal,
            'intensity' => $level,
            'equipmentneeded' => $location,
            'target' => ['Leg', 'Abs'], // Adjust this according to your database schema
        ]);
    
        // You can return these as an array or any other format you prefer
        $workoutPlan = [
            'ChestShoulderDay' => $chestShoulderExercises,
            'BackArmDay' => $backArmExercises,
            'LegAbsDay' => $legAbsExercises,
        ];

        return $workoutPlan;
    }
    
    public function sendSms($phone,$brandName,$message){
        $basic  = new \Vonage\Client\Credentials\Basic("bb5f8701", "u9zRzmNvwDvjsRLg");
        $client = new \Vonage\Client($basic);

            $response = $client->sms()->send(
                new \Vonage\SMS\Message\SMS( $phone , $brandName, $message)
            );

            $message = $response->current();

            if ($message->getStatus() == 0) {
                echo "The message was sent successfully\n";
            } else {
                echo "The message failed with status: " . $message->getStatus() . "\n";
            }
        
        
        
    }


    public function MessageGenerate($workoutPlan){
        $message = "Your workout plan for today:\n";

        foreach ($workoutPlan as $day => $exercises) {
            $message .= "\n$day:\n";
    
            foreach ($exercises as $exercise) {
                // Assuming $exercise is an instance of Exercise class with a getName() method
                $message .= "- " . $exercise->getName() . "\n";
            }
        }
        return $message;
    }
}
