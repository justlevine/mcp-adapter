<?php
/**
 * Transport context object for dependency injection.
 *
 * @package McpAdapter
 */

declare( strict_types=1 );

namespace WP\MCP\Transport\Infrastructure;

use WP\MCP\Core\McpServer;
use WP\MCP\Handlers\Initialize\InitializeHandler;
use WP\MCP\Handlers\Prompts\PromptsHandler;
use WP\MCP\Handlers\Resources\ResourcesHandler;
use WP\MCP\Handlers\System\SystemHandler;
use WP\MCP\Handlers\Tools\ToolsHandler;

/**
 * Transport context object for dependency injection.
 *
 * Contains all dependencies needed by transport implementations,
 * promoting loose coupling and easier testing.
 */
class McpTransportContext {

	/**
	 * @var \WP\MCP\Core\McpServer
	 */
	public McpServer $mcp_server;
	/**
	 * @var \WP\MCP\Handlers\Initialize\InitializeHandler
	 */
	public InitializeHandler $initialize_handler;
	/**
	 * @var \WP\MCP\Handlers\Tools\ToolsHandler
	 */
	public ToolsHandler $tools_handler;
	/**
	 * @var \WP\MCP\Handlers\Resources\ResourcesHandler
	 */
	public ResourcesHandler $resources_handler;
	/**
	 * @var \WP\MCP\Handlers\Prompts\PromptsHandler
	 */
	public PromptsHandler $prompts_handler;
	/**
	 * @var \WP\MCP\Handlers\System\SystemHandler
	 */
	public SystemHandler $system_handler;
	/**
	 * @var string
	 */
	public string $observability_handler;
	/**
	 * @var \WP\MCP\Transport\Infrastructure\McpRequestRouter|null
	 */
	public ?McpRequestRouter $request_router;
	/**
	 * @var callable|null
	 */
	public $transport_permission_callback = null;
	/**
	 * Initialize the transport context.
	 *
	 * @param \WP\MCP\Core\McpServer $mcp_server The MCP server instance.
	 * @param \WP\MCP\Handlers\Initialize\InitializeHandler $initialize_handler The initialize handler.
	 * @param \WP\MCP\Handlers\Tools\ToolsHandler $tools_handler The tools handler.
	 * @param \WP\MCP\Handlers\Resources\ResourcesHandler $resources_handler The resources handler.
	 * @param \WP\MCP\Handlers\Prompts\PromptsHandler $prompts_handler The prompts handler.
	 * @param \WP\MCP\Handlers\System\SystemHandler $system_handler The system handler.
	 * @param string                $observability_handler The observability handler class name.
	 * @param \WP\MCP\Transport\Infrastructure\McpRequestRouter|null $request_router The request router service.
	 * @param callable|null         $transport_permission_callback Optional custom permission callback for transport-level authentication.
	 */
	public function __construct( McpServer $mcp_server, InitializeHandler $initialize_handler, ToolsHandler $tools_handler, ResourcesHandler $resources_handler, PromptsHandler $prompts_handler, SystemHandler $system_handler, string $observability_handler, ?McpRequestRouter $request_router, $transport_permission_callback = null ) {
		$this->mcp_server                    = $mcp_server;
		$this->initialize_handler            = $initialize_handler;
		$this->tools_handler                 = $tools_handler;
		$this->resources_handler             = $resources_handler;
		$this->prompts_handler               = $prompts_handler;
		$this->system_handler                = $system_handler;
		$this->observability_handler         = $observability_handler;
		$this->request_router                = $request_router;
		$this->transport_permission_callback = $transport_permission_callback;
	}
}
