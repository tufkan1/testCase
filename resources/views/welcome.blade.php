<!DOCTYPE html>
<html>
<head>
    <title>Laravel</title>
    <script src="https://cdn.jsdelivr.net/npm/vue@3.4.22/dist/vue.global.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset("/css/style.css") }}">
</head>
<body>
<div id="app">
    <div class="container my-5">
        <div class="row standings-box" v-if="standing">
            <div class="col-md-12">
                <h2 class="standings-box_title text-dark my-3">Football Fixture</h2>
                <div class="table-responsive">
                    <table id="standings-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Teams</th>
                                <th>P</th>
                                <th>W</th>
                                <th>D</th>
                                <th>L</th>
                                <th>GD</th>
                                <th>PTS</th>
                            </tr>
                        </thead>
                        <tbody>
                        <tr v-for="team in standing" :key="team.id">
                            <td>
                                <img :src="'/images/' + team.logo" alt="team logo" width="50" class="me-3">
                                @{{ team.name }}
                            </td>
                            <td>@{{ team.played }}</td>
                            <td>@{{ team.won }}</td>
                            <td>@{{ team.draw }}</td>
                            <td>@{{ team.lose }}</td>
                            <td>@{{ team.goal_difference }}</td>
                            <td>@{{ team.points }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="row fixtures-box" v-if="weeks">
            <div class="col-md-8">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <td class="make-center" colspan="3">
                            <h3>
                                Future Fixtures
                            </h3>
                        </td>
                    </tr>
                    </thead>
                    <tbody id="table-body">
                    <template v-if="weeks">
                        <template v-for="week in weeks" :key="week.id">
                            <tr>
                                <td colspan="3" class="fixtures-box_header pt-5">
                                    @{{ week.title }} Week Matches
                                </td>
                            </tr>
                            <template v-if="matches">
                                <template v-if="matches[week.id] && matches[week.id].length >= 2">
                                    <template v-for="fixture in matches[week.id]" :key="fixture.id">
                                        <tr>
                                            <td class="make-center">
                                                <img width="30" height="30" src="/images/home_blue.png">
                                                <img width="60" :src="'/images/' + fixture.home_shirt">
                                                @{{ fixture.home_team }}
                                            </td>
                                            <td class="make-center">@{{ fixture.home_team_goal }} - @{{ fixture.away_team_goal }}</td>
                                            <td class="make-center">
                                                <img width="30" height="30" src="/images/airplane_blue.png">
                                                <img width="60" :src="'/images/' + fixture.away_shirt">
                                                @{{ fixture.away_team }}
                                            </td>
                                        </tr>
                                    </template>
                                    <tr v-if="matches[week.id][0]?.status === 0 && matches[week.id][1]?.status === 0">
                                        <td colspan="5" class="make-center weekly-simulate-button">
                                            <button @click="playWeek(week.id)" class="btn btn-primary play-week">
                                                Simulate @{{ week.title }} Week
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                            </template>

                        </template>
                    </template>

                    </tbody>
                </table>
            </div>
            <div class="col-md-4">
                <div class="row">
                    <h3 class="make-center make-full-width">Simulation Managements</h3>
                    <div class="make-center make-full-width">
                        <button class="btn btn-success simulate-all-weeks" @click="simulateAll()">Simulate All Weeks</button>
                    </div>
                    <div class="make-full-width make-center">
                        <button class="btn btn-danger reset-all" @click="resetAll()">Reset All</button>
                    </div>
                </div>
                <div class="row prediction-wrapper">
                    <h3 class="prediction-box_title">Champion Prediction</h3>
                    <table class='table table-dark make-full-width'>
                        <thead>
                        <tr>
                            <th scope='col'>Team</th>
                            <th scope='col'>Percentage</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(percent, team) in predictions" :key="team">
                            <th scope='row'>@{{ team }}</th>
                            <td>@{{ percent }}%</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var standingData = @json($standing ?? []);
    var weeksData = @json($weeks ?? []);
    var matchesData = @json($matches ?? []);
    var predictionsData = @json($predictions ?? []);

    const { createApp, ref } = Vue;

    createApp({
        setup() {
            const standing = ref(standingData);
            const weeks = ref(weeksData);
            const matches = ref(matchesData);
            const predictions = ref(predictionsData);

            function showHideResetAllButton(hide = true){
                const resetAllButton = document.querySelector('.reset-all');
                if (hide) {
                    resetAllButton.classList.add('d-none');
                } else {
                    resetAllButton.classList.remove('d-none');
                }
            }

            function showHideSimulateAllButton(hide = true){
                const simulateAllButton = document.querySelector('.simulate-all-weeks');
                if (hide) {
                    simulateAllButton.classList.add('d-none');
                } else {
                    simulateAllButton.classList.remove('d-none');
                }
            }

            function refreshFixture() {
                fetch("/fixtures")
                    .then(response => response.json())
                    .then(data => {
                        weeks.value = data.weeks;
                        matches.value = data.items;
                    });
            }

            function refreshStanding() {
                fetch("/standings")
                    .then(response => response.json())
                    .then(data => {
                        standing.value = data;
                    });
            }

            function prediction() {
                fetch("/prediction")
                    .then(response => response.json())
                    .then(data => {
                        predictions.value = data.items;
                    });
            }

            function playWeek(weekId) {
                fetch(`/play-week/${weekId}`)
                    .then(response => response.json())
                    .then(data => {
                        matches.value = data.matches;
                        standing.value = data.standing;
                        predictions.value = data.predictions;
                        refreshFixture();
                        refreshStanding();
                        prediction();
                    });

                showHideResetAllButton(false);
            }

            function resetAll() {
                fetch("/reset-all")
                    .then(response => response.json())
                    .then(data => {
                        matches.value = data.matches;
                        standing.value = data.standing;
                        predictions.value = data.predictions;
                        refreshFixture();
                        refreshStanding();
                        prediction();
                    });

                showHideSimulateAllButton(false);
                showHideResetAllButton(true);
            }

            function simulateAll() {
                fetch("/play-all-weeks")
                    .then(response => response.json())
                    .then(data => {
                        matches.value = data.matches;
                        standing.value = data.standing;
                        predictions.value = data.predictions;
                        refreshFixture();
                        refreshStanding();
                        prediction();
                    });

                showHideSimulateAllButton(true);
                showHideResetAllButton(false);
            }


            return {
                standing,
                weeks,
                matches,
                predictions,
                playWeek,
                refreshFixture,
                refreshStanding,
                prediction,
                simulateAll,
                resetAll
            };
        }
    }).mount('#app');
</script>


</body>
</html>
