<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Widget_Github_repos extends Widgets
{
    // The widget title,  this is displayed in the admin interface
    public $title = 'GitHub Repos';

    //The widget description, this is also displayed in the admin interface.  Keep it brief.
    public $description =  'Display a list of your GitHub repositories.';

    // The author's name
    public $author = 'Zac Vineyard';

    // The authors website for the widget
    public $website = 'http://zacvineyard.com/';

    //current version of your widget
    public $version = '1.0';

    /**
     * $fields array fore storing widget options in the database.
     * values submited through the widget instance form are serialized and
     * stored in the database.
     */
    public $fields = array(
        array(
            'field'   => 'github_user_id',
            'label'   => 'GitHub User ID',
            'rules'   => 'required'
        )
    );

    /**
     * Get the Data from GitHub
     */
    public function list_repos($user)
    {
        // Get public gist JSON
        $url = "https://api.github.com/users/".$user."/repos?sort=updated";

        // Check cache
        if (!$data = $this->pyrocache->get('github_repos_widget'))
        { 
            $data = file_get_contents($url);
            $this->pyrocache->write($data, 'github_repos_widget', 43200); // Expires every 12 hours
        }

        // Check cache
        $data = json_decode(file_get_contents($url)); // decoded JSON
        if($data)
        {
            return $data;
        }
        else
        {
            return false;
        }
    }

    /**
     * the $options param is passed by the core Widget class.  If you have
     * stored options in the database,  you must pass the paramater to access
     * them.
     */
    public function run($options)
    {
        if(!empty($options['github_user_id']))
        {
            // Get the repos
            $repos = $this->list_repos($options['github_user_id']);
        }
        else
        {
            return array('output' => '');
        }

        // Store the items
        return array(
            'github_user_id' => $options['github_user_id'],
            'repos' => $repos ? $repos : array()
        );
    }

}