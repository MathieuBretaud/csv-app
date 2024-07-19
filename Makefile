

imports:
	symfony console app:import-contacts
.PHONY: imports

updates:
	symfony console app:update-contacts
.PHONY: updates

removes:
	symfony console app:remove-contacts
.PHONY: removes