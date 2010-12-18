<?php
class Terms_API {

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

    public function add_term($json_encoded_parameters)
    {
        $workflow = new \Swiftriver\Core\Workflows\TermServices\AddTerm();
        $json = $workflow->RunWorkflow($json_encoded_parameters, $this->apiKey);
        return $json;
    }

    public function get_all_terms()
    {
        $workflow = new \Swiftriver\Core\Workflows\TermServices\GetAllTerms();
        $json = $workflow->RunWorkflow($this->apiKey);
        return $json;
    }
}