<?php
class Tags_API {

    /**
     * The core API key
     * @var string Guid
     */
    private $apiKey;

    /**
     * Constructor method used to include the core setup file
     * before any of the api functions can be called
     */
    public function __construct($apiKey)
    {
        //Localise the api key
        $this->apiKey = $apiKey;

        //include the core one
        include_once(DOCROOT."../core/Setup.php");
    }

    public function get_all_tags()
    {
        //Instanciate the workflow
        $workflow = new \Swiftriver\Core\Workflows\TagServices\GetAllTags();

        //run the workflow
        $json = $workflow->RunWorkflow($this->apiKey);

        //return the json
        return $json;
    }

    public function get_common_tags($num_tags)
    {
        //Instanciate the workflow
        $workflow = new \Swiftriver\Core\Workflows\TagServices\GetCommonTags();

        //run the workflow
        $json = $workflow->RunWorkflow($num_tags, $this->apiKey);

        //return the json
        return $json;
    }
}
?>