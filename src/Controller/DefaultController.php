<?php

namespace App\Controller;

use App\Api\GitHubResumeGenerator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
Use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends AbstractController 
{
    /**
     * @Route("/", name="home")
     */
    public function indexAction(Request $request)
    {
        $username = $request->get('username', false);
        $theme = $request->get('theme', 1);

        $data = [];

        if ($username) {
            $generator = new GitHubResumeGenerator($username);
            $data['profile'] = $generator->getProfile();

            if (!empty($data['profile'])) {
                $data['repositories'] = $generator->getRepositories();
                $data['languages'] = $generator->getLanguages($data['repositories']);
                $data['organizations'] = $generator->getOrganizations();
                $data['contributions'] = $generator->getContributions($generator->getIssues());
                $data['favourites'] = $generator->getFavourites();

                if ($theme == 2){
                    return $this->render('resume_template/resume-template-2.html.twig', $data);
                }elseif ($theme == 3){
                    return $this->render('resume_template/resume-template.html.twig', $data);
                }
            }
        }

        return $this->render('index.html.twig', $data);
    }

}
