<?php
require_once(__DIR__ . '/vendor/autoload.php');
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
header("Access-Control-Allow-Origin: *");

$openaiClient = \Tectalic\OpenAi\Manager::build(
    new \GuzzleHttp\Client(),
    new \Tectalic\OpenAi\Authentication($_ENV['CHATGPT_KEY'])
);

$json = file_get_contents('php://input');
$data = json_decode($json, true);

$prompt = [
    'role' => 'user',
    'content' => $data['prompt']
];

// {
//     "prompt":"Employees of [Meolock] were surveyed, using Priority Bridge comparative judgement based assessments for prioritizing the 9 dimensions of the company's culture. The ranking of [Meolock] 9 culture dimensions, based on employees' comparative judgement surveys, are as follows: [1.Customer Focus, Deliver For Our Clients 41.872% 2. Meritocratic, Recognize Achievement, Results Driven 13.364% 3. Cutting Edge, Leading Change, Advanced Tech 11.348% 4. Teamwork, One Company 10.68% 5. Treat With Dignity, Courtesy, Appreciation For Each Other 8.427% 6. Do The Right Thing, Be Ethical, Play By The Rules 4.945% 7. Operational Excellence, Projects Managed Well 4.646% 8. Flexibility, Nimble 3.038% 9. Inclusion, Everyone Is Welcome, Celebrate Differences 1.682%] Based on the ranking above, write an insightful and non-obvious summary of [Meolock] organizational culture for [Northeast Operation]. Give your response in HTML format"
//  }

/** @var \Tectalic\OpenAi\Models\ChatCompletions\CreateResponse $response */
$response = $openaiClient->chatCompletions()->create(
    new \Tectalic\OpenAi\Models\ChatCompletions\CreateRequest([
        'model' => 'gpt-4',
        'messages' => [$prompt],
    ])
)->toModel();
header("Content-Type: text/plain");
// header("Content-Type: text/html");
echo $response->choices[0]->message->content;



?>